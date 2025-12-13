<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\Department;
use App\Models\User;
use App\Models\Approval;
use App\Models\ApprovalLevel;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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


    if (!$user->hasRole('super-admin')) {
        $requestsQuery->where('requestor_id', $user->id) // Jo usne banayi
            ->orWhere('department_id', $user->department_id) // Jo uske department ki hai
            ->orWhereHas('approvals', function ($query) use ($user) {
                $query->where('approver_id', $user->id) // Jo usko assign hain
                    ->where('status', 'pending');
            });
    }

    // Filters
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
   
    // === STEP 1: Validation (Updated for Private/General) ===
    $validatedData = $request->validate([
        // requestor_id ko Auth::id() se nikaalna behtar hai, user input se nahi
        'requestor_id' => 'nullable|exists:users,id', // Agar admin doosre users ke liye request bana raha ho
        
        // New Fields Validation
        'type' => 'required|in:general,private',
        'assigned_to_user_id' => 'required_if:type,private|nullable|exists:users,id',
        'department_id' => 'required_if:type,general|nullable|exists:departments,id',

        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'amount' => 'required|numeric|min:0.01',
        // 'attachments' should be validated here if not using Spatie's method
    ]);

    $initialLevel = 1;
    $requestorId = Auth::id(); // Request hamesha logged-in user create karta hai

    // === STEP 2: Request Creation (Transaction Safety) ===
    DB::beginTransaction();
    try {
        $requestModel = RequestModel::create([
            'requestor_id' => $requestorId,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'amount' => $validatedData['amount'],
            
            'type' => $validatedData['type'],
            'assigned_to_user_id' => $validatedData['assigned_to_user_id'] ?? null,
            
            // General Request ke liye department zaroori hai
            'department_id' => $validatedData['type'] === 'general' ? $validatedData['department_id'] : null,
            
            'status' => 'pending',
            'current_level' => $initialLevel,
        ]);

        // Attachments handling (Spatie Media Library)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $requestModel->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // === STEP 3: Approval Initialization (Logic Split) ===
        $approverId = null;

        if ($requestModel->type === 'private') {
            // Scenario 1: Private Request - Approver wohi user hai jisko assign kiya gaya hai
            $approverId = $requestModel->assigned_to_user_id;
            // Private requests mein Level 1 hi aakhri level hota hai.
            
        } else {

            // Scenario 2: General Request - Level 1 Approver dhoondhein
            $levelOne = ApprovalLevel::where('department_id', $requestModel->department_id)
                ->where('sequence', 1)
                ->first();
    

            if (!$levelOne) {
                // Agar koi Approval Level define nahi hai (Department Z jaisa scenario)
                $requestModel->update(['status' => 'approved', 'current_level' => 1]);
                DB::commit();
                
                return redirect()->route('requests.index')->with('success', 'Request created and automatically approved (No workflow found).');
            }

            $approverId = optional($levelOne->users()->first())->id;

            if (!$approverId) {
                // Agar Approver ki ID nahi mili
                $requestModel->update(['status' => 'Needs Approver']);
                DB::commit();
                return redirect()->route('requests.index')->with('warning', 'Request created, but Level 1 Approver is missing.');
            }
        }
        
        // Final Pending Entry Creation (Dono Scenarios ke liye)
        Approval::create([
            'request_id' => $requestModel->id,
            'approver_id' => $approverId,
            'level' => $initialLevel,
            'status' => 'pending',
        ]);

        DB::commit();
        return redirect()->route('requests.index')->with('success', 'Request submitted successfully.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        // dd($e->getMessage(), $e->getFile(), $e->getLine());
        // Exception ko log karna zaroori hai
        \Log::error("Request submission failed: " . $e->getMessage(), ['user_id' => $requestorId]);
        return back()->with('error', 'Request could not be submitted. Please try again.');
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
    
    // Authorization check
    $user = auth()->user();
    if (!$user->hasRole('super-admin') && $requestModel->requestor_id !== $user->id) {
        abort(403, 'Unauthorized action.');
    }
    
    // Status check
    if (in_array($requestModel->status, ['approved', 'rejected'])) {
        return redirect()->route('requests.index')
            ->with('error', 'Cannot update a request that is already ' . $requestModel->status);
    }

    // Validation - Same as store method
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
        // Check if type or assignment changed
        $typeChanged = $requestModel->type !== $validatedData['type'];
        $assignmentChanged = false;
        
        if ($validatedData['type'] === 'private') {
            $assignmentChanged = $requestModel->assigned_to_user_id !== $validatedData['assigned_to_user_id'];
        } else {
            $assignmentChanged = $requestModel->department_id !== $validatedData['department_id'];
        }

        // Update request fields
        $requestModel->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'amount' => $validatedData['amount'],
            'type' => $validatedData['type'],
            'assigned_to_user_id' => $validatedData['assigned_to_user_id'] ?? null,
            'department_id' => $validatedData['type'] === 'general' ? $validatedData['department_id'] : null,
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            // Replace attachments option
            if ($request->input('replace_attachments')) {
                $requestModel->clearMediaCollection('attachments');
            }
            
            foreach ($request->file('attachments') as $file) {
                $requestModel->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // Agar type ya assignment change hui hai, to approvals reset karni hongi
        if ($typeChanged || $assignmentChanged) {
            // Delete existing pending approvals
            $requestModel->approvals()->where('status', 'pending')->delete();
            
            // Reset to level 1
            $requestModel->update([
                'current_level' => 1,
                'status' => 'pending'
            ]);
            
            // Create new approval entry
            $approverId = null;
            
            if ($requestModel->type === 'private') {
                $approverId = $requestModel->assigned_to_user_id;
            } else {
                $levelOne = ApprovalLevel::where('department_id', $requestModel->department_id)
                    ->where('sequence', 1)
                    ->first();
                
                if (!$levelOne) {
                    $requestModel->update(['status' => 'approved', 'current_level' => 1]);
                    DB::commit();
                    return redirect()->route('requests.index')
                        ->with('success', 'Request updated and automatically approved (No workflow found).');
                }
                
                $approverId = optional($levelOne->users()->first())->id;
                
                if (!$approverId) {
                    $requestModel->update(['status' => 'Needs Approver']);
                    DB::commit();
                    return redirect()->route('requests.index')
                        ->with('warning', 'Request updated, but Level 1 Approver is missing.');
                }
            }
            
            Approval::create([
                'request_id' => $requestModel->id,
                'approver_id' => $approverId,
                'level' => 1,
                'status' => 'pending',
            ]);
        }

        DB::commit();
        return redirect()->route('requests.index')
            ->with('success', 'Request updated successfully.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Request update failed: " . $e->getMessage(), [
            'request_id' => $id,
            'user_id' => $user->id
        ]);
        return back()->with('error', 'Request could not be updated. Please try again.');
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $req = RequestModel::findOrFail($id);
        $req->delete();

        return redirect()->route('requests.index')->with('success', 'Request deleted successfully.');
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

}