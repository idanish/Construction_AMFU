<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\Department;
use App\Models\User;
use App\Models\Approval;
use App\Models\ApprovalLevel;
use App\Models\Notification;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $r)
    {
        $perPage = $r->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        $user = auth()->user();
        $requestsQuery = RequestModel::with(['requestor', 'department'])->latest();

        // 1. Permissions Logic (Grouping zaroori hai)
        if (!$user->hasRole('super-admin')) {
            $requestsQuery->where(function ($query) use ($user) {
                $query->where('requestor_id', $user->id) // Jo mene banayi
                    ->orWhere('department_id', $user->department_id) // Mere department ki
                    ->orWhereHas('approvals', function ($q) use ($user) {
                        $q->where('approver_id', $user->id); // Jin mein mera koi role hai
                    });
            });
        }

        // 2. Filters (Ab ye sahi kaam karenge kyunke upar wala block group ho gaya hai)
        if ($r->filled('requestor_id')) {
            $requestsQuery->where('requestor_id', $r->requestor_id);
        }
        
        if ($r->filled('status')) {
            $requestsQuery->where('status', $r->status);
        }

        if ($r->filled('start_date') && $r->filled('end_date')) {
            $requestsQuery->whereBetween('created_at', [
                $r->start_date . " 00:00:00",
                $r->end_date . " 23:59:59"
            ]);
        }

        $requests = $requestsQuery->paginate($perPage)->withQueryString();
        $departments = Department::all();
        $allRequestors = User::orderBy('name')->get();

        return view('requests.index', compact('requests', 'departments', 'allRequestors'));
    }

    public function create()
    {

        $departments = Department::all();
        $users = User::orderBy('name')->get();

        return view('requests.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        // === STEP 1: Validation ===
        $validatedData = $request->validate([
            'requestor_id' => 'nullable|exists:users,id',
            'type' => 'required|in:general,private',
            'assigned_to_user_id' => 'required_if:type,private|nullable|exists:users,id',
            'department_id' => 'required_if:type,general|nullable|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $requestorId = Auth::id();

        DB::beginTransaction();
        try {
            // === STEP 2: Request Creation ===
            $requestModel = RequestModel::create([
                'requestor_id' => $requestorId,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'amount' => $validatedData['amount'],
                'type' => $validatedData['type'],
                'assigned_to_user_id' => $validatedData['assigned_to_user_id'] ?? null,
                'department_id' => $validatedData['type'] === 'general' ? $validatedData['department_id'] : null,
                'status' => 'pending',
                'current_level' => 1,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $requestModel->addMedia($file)->toMediaCollection('attachments');
                }
            }

            // === STEP 3: Approval Initialization (The Clean Way) ===
            if ($requestModel->type === 'private') {
                Approval::create([
                    'request_id' => $requestModel->id,
                    'approver_id' => $requestModel->assigned_to_user_id,
                    'level' => 1,
                    'status' => 'pending',
                ]);
            } else {
                // Sirf isse rehne dein, baaki extra code delete kar dein
                $this->initializeWorkflow($requestModel);
            }

            DB::commit();

            // Email logic...
            return redirect()->route('requests.index')->with('success', 'Request submitted successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Request submission failed: " . $e->getMessage());
            return back()->with('error', 'Request could not be submitted.');
        }
    }

    public function show($id)
    {
        $request = RequestModel::with(['requestor', 'department'])->findOrFail($id);
        return view('requests.show', compact('request'));
    }

    public function edit($id)
    {
        $requestModel = RequestModel::with(['media', 'requestor', 'department'])->findOrFail($id);
        
        // Check authorization - sirf requestor ya super-admin edit kar sakta hai
        $user = auth()->user();
        if (!$user->hasRole('super-admin') && $requestModel->requestor_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Agar request already approved/rejected hai to edit nahi hona chahiye
        if (in_array($requestModel->status, ['approved', 'rejected'])) {
            return redirect()->route('requests.index')
                ->with('error', 'Cannot edit a request that is already ' . $requestModel->status);
        }
        
        $departments = Department::all();
        $users = User::orderBy('name')->get();

        return view('requests.edit', compact('requestModel', 'departments', 'users'));
    }

    public function update(Request $request, $id)
    {
        $requestModel = RequestModel::findOrFail($id);
        $user = auth()->user();

        // Authorization & Status Checks (Sahi hain)
        if (!$user->hasRole('super-admin') && $requestModel->requestor_id !== $user->id) { abort(403); }
        if (in_array($requestModel->status, ['approved', 'rejected'])) {
            return redirect()->route('requests.index')->with('error', 'Cannot update final status request.');
        }

        $validatedData = $request->validate([
            'type' => 'required|in:general,private',
            'assigned_to_user_id' => 'required_if:type,private|nullable|exists:users,id',
            'department_id' => 'required_if:type,general|nullable|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $typeChanged = $requestModel->type !== $validatedData['type'];
            $assignmentChanged = ($validatedData['type'] === 'private') 
                ? ($requestModel->assigned_to_user_id !== $validatedData['assigned_to_user_id'])
                : ($requestModel->department_id !== $validatedData['department_id']);

            $requestModel->update([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'amount' => $validatedData['amount'],
                'type' => $validatedData['type'],
                'assigned_to_user_id' => $validatedData['assigned_to_user_id'] ?? null,
                'department_id' => $validatedData['type'] === 'general' ? $validatedData['department_id'] : null,
            ]);

            // Attachments logic (Sahi hai)

            if ($typeChanged || $assignmentChanged) {
                // Purani pending approvals delete karein
                $requestModel->approvals()->where('status', 'pending')->delete();
                
                // Workflow reset karein
                if ($requestModel->type === 'private') {
                    Approval::create([
                        'request_id' => $requestModel->id,
                        'approver_id' => $requestModel->assigned_to_user_id,
                        'level' => 1,
                        'status' => 'pending',
                    ]);
                } else {
                    // UPDATE MEIN BHI SKIP LOGIC CHALAYEIN
                    $this->initializeWorkflow($requestModel);
                }
            }

            DB::commit();
            return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update failed.');
        }
    }

    public function destroy($id)
    {
        $req = RequestModel::findOrFail($id);
        $user = auth()->user();

        // Authorization: Sirf requestor ya Super Admin delete kar sake
        if (!$user->hasRole('super-admin') && $req->requestor_id !== $user->id) {
            abort(403, 'You cannot delete this request.');
        }

        DB::beginTransaction();
        try {
            // 1. Pehle attachments delete karein (Spatie Media Library)
            $req->clearMediaCollection('attachments');

            // 2. Phir saari approvals delete karein
            $req->approvals()->delete();

            // 3. Last mein main request delete karein
            $req->delete();

            DB::commit();
            return redirect()->route('requests.index')->with('success', 'Request has been deleted.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Deletion failed: " . $e->getMessage());
            return back()->with('error', 'Error on deleting.');
        }
    }

    // Approved Request
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        $request_item = RequestModel::find($id);

        if ($request_item) {
            $request_item->status = $request->input('status');
            $request_item->save();
            
            return redirect()->back()->with('success', 'Status updated successfully!');
        }

        return redirect()->back()->with('error', 'Request not found!');
    }

    private function initializeWorkflow($requestModel, $currentSequence = 1)
    {
        // 1. Pehle ye check karein ke Requestor ka apna level kya hai is department mein
        $requestorLevel = ApprovalLevel::where('department_id', $requestModel->department_id)
            ->whereHas('users', function($q) use ($requestModel) {
                $q->where('users.id', $requestModel->requestor_id);
            })->first();

        $requestorSequence = $requestorLevel ? $requestorLevel->sequence : 0;

        // 2. Agar current level requestor ke level se chota hai, toh usse auto-skip karein
        if ($currentSequence < $requestorSequence) {
            $bypassComment = "System: Level {$currentSequence} bypassed (Requestor is at Level {$requestorSequence}).";
    
            Approval::create([
                'request_id'  => $requestModel->id,
                'approver_id' => $requestModel->requestor_id,
                'level'       => $currentSequence,
                'status'      => 'approved',
                'comments'    => $bypassComment
            ]);

            // Update Request Table Comment & Level
            $requestModel->update([
                'current_level' => $currentSequence,
                'comments' => $bypassComment
            ]);
            
            // Bina approval record banaye seedha agle level par jump karein
            return $this->initializeWorkflow($requestModel, $currentSequence + 1);
        }

        // --- Baqi standard logic yahan se shuru hogi ---

        $level = ApprovalLevel::where('department_id', $requestModel->department_id)
            ->where('sequence', $currentSequence)
            ->first();

        // Workflow khatam (Final Approval)
        if (!$level) {
            $requestModel->update(['status' => 'approved']);
            return;
        }

        $approverId = optional($level->users()->first())->id;

        if (!$approverId) {
            $requestModel->update(['status' => 'Needs Approver', 'current_level' => $currentSequence]);
            return;
        }

        // 3. SELF-APPROVAL SKIP: Agar Requestor khud is level ka approver hai
        if ($approverId === $requestModel->requestor_id) {
            $selfApproveComment = 'System: Auto-approved (Requestor level match at Level '.$currentSequence.').';

            Approval::create([
                'request_id'  => $requestModel->id,
                'approver_id' => $approverId,
                'level'       => $currentSequence,
                'status'      => 'approved',
                'comments'    => $selfApproveComment
            ]);

            $requestModel->update([
                'current_level' => $currentSequence,
                'comments' => $selfApproveComment
            ]);

            return $this->initializeWorkflow($requestModel, $currentSequence + 1);
        } else {
            // Standard Case: Agle bande ke liye pending karein
            $requestModel->update(['current_level' => $currentSequence, 'status' => 'pending']);
            Approval::create([
                'request_id'  => $requestModel->id,
                'approver_id' => $approverId,
                'level'       => $currentSequence,
                'status'      => 'pending',
            ]);
            
            $this->sendNotification($approverId, "New Approval Required", "Request #{$requestModel->id} is pending.");
        }
    }

    private function sendNotification($userId, $title, $message, $role = null)
    {
        if ($userId || $role) {
            \App\Models\Notification::create([
                'user_id' => $userId,
                'role'    => $role,
                'type'    => 'approve', 
                'message' => $title . ": " . $message,
                'is_read' => false,
                'transaction_no' => 0,
            ]);
        }

        // Email logic (Using Mailpit)
        try {
            $email = null;
            if ($userId) {
                $user = \App\Models\User::find($userId);
                $email = $user ? $user->email : null;
            }

            if ($email) {
                \Mail::raw($message, function ($mail) use ($email, $title) {
                    $mail->to($email)->subject($title);
                });
            }
        } catch (\Exception $e) {
            \Log::error("Mail Error in RequestController: " . $e->getMessage());
        }
    }
}