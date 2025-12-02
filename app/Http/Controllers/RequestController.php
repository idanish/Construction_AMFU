<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $r)
    {
        // 1. Pagination Setup
        $perPage = $r->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        $user = auth()->user();
        $requestsQuery = RequestModel::with(['requestor', 'department'])->latest();

        // 2. Department filter - only see own department or own requests
        if (!$user || !$user->hasRole('Admin')) {
            $requestsQuery->where(function ($q) use ($user) {
                $q->where('department_id', $user->department_id)
                  ->orWhere('requestor_id', $user->id);
            });
        }

        // 3. Filters Implementation

        // Filter By Requestor (requestor_id)
        if ($r->filled('requestor_id')) {
            $requestsQuery->where('requestor_id', $r->requestor_id);
        }

        // Filter By Status (status)
        if ($r->filled('status')) {
            $requestsQuery->where('status', $r->status);
        }

        // Date Range Filter (start_date and end_date)
        if ($r->filled('start_date') && $r->filled('end_date')) {
            $requestsQuery->whereBetween('created_at', [
                $r->start_date . " 00:00:00",
                $r->end_date . " 23:59:59"
            ]);
        }

        // Final Data Fetching
        $requests = $requestsQuery->paginate($perPage)->withQueryString();
        
        $departments = Department::all();
        $allRequestors = User::orderBy('name')->get();

        return view('requests.index', compact('requests', 'departments', 'allRequestors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();

        return view('requests.create', compact('departments'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'requestor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $data = array_merge($request->only(['requestor_id','department_id','title','description','amount']), 
            ['status' => 'Pending', 'current_approval_step' => 'PM']);
        $req = RequestModel::create($data);

        // Create approval rows for sequential workflow
        \App\Http\Controllers\ApprovalController::createApprovalsForRequest($req);

        return redirect()->route('requests.index')->with('success', 'Request created and sent to PM for approval.');
    }

    public function show($id)
    {
        $request = RequestModel::with(['requestor', 'department'])->findOrFail($id);
        return view('requests.show', compact('request'));
    }

    public function edit($id) 
{
    $request = RequestModel::findOrFail($id);

    // Add this line to fetch all departments
    $departments = Department::all();

    return view('requests.edit', compact('request', 'departments'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'requestor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $req = RequestModel::findOrFail($id);
        $originalStatus = $req->status;

        $req->update([
            'requestor_id' => $request->requestor_id,
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        // If request was reverted, reset approvals and send to PM step 1
        if ($originalStatus === 'reverted') {
            $req->status = 'Pending';
            $req->current_approval_step = 'PM';
            $req->revert_reason = null;
            $req->save();

            // Reset all approval rows to pending
            $req->approvals()->update([
                'status' => 'pending',
                'note' => null,
                'acted_at' => null,
                'revert_reason' => null
            ]);

            // Notify PM (first approver) that request has been resubmitted
            try {
                $pmApproval = $req->approvals()->where('approval_step', 'PM')->first();
                if ($pmApproval) {
                    $pm = $pmApproval->approver;
                    createNotification(null, "Request (#{$req->id}) from {$req->requestor->name} has been resubmitted and is waiting for your PM approval.", $pm->id, 'approval');
                    
                    if ($pm && $pm->email) {
                        \Mail::raw("Request (#{$req->id}) has been resubmitted and is waiting for your PM approval.\n\nTitle: {$req->title}\nAmount: {$req->amount}", 
                            function ($m) use ($pm) {
                                $m->to($pm->email)->subject('Request Resubmitted - Pending PM Approval');
                            }
                        );
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to notify PM of resubmission: ' . $e->getMessage());
            }
        }

        return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
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