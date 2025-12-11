<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\ApprovalLevel;
use App\Models\Department;
use App\Models\RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    /**
     * Show approvals that CURRENT USER must take action on
     */
    public function index()
    {
        $user = Auth::user();

        $approvals = Approval::with('request')
            ->where('approver_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('approvals.index', compact('approvals'));
    }


    /**
     * Approve or Reject a specific approval step
     */
    public function updateStatus(Request $req, $approvalId)
    {
        $req->validate([
            'status'   => 'required|in:approved,rejected',
            'comments' => 'nullable|string|max:500',
        ]);

        $approval = Approval::with('request')->findOrFail($approvalId);

        // --- Security Check: Only correct approver can approve ---
        if ($approval->approver_id !== Auth::id()) {
            return back()->with('error', "You are not authorized for this approval level.");
        }

        // Update current approval level
        $approval->update([
            'status'   => $req->status,
            'comments' => $req->comments,
        ]);

        // If rejected → request rejected & no more levels required
        if ($req->status === 'rejected') {
            $approval->request->update(['status' => 'rejected']);
            return back()->with('success', "Request rejected successfully.");
        }

        // If approved → create next level (if exists)
        $request = $approval->request;

        // Next approval level sequence
        $nextSequence = $approval->level + 1;

        // Check if next level exists for this department
        $nextLevel = ApprovalLevel::where('department_id', $request->department_id)
            ->where('sequence', $nextSequence)
            ->first();

        // If no next level → final approval
        if (!$nextLevel) {
            $request->update(['status' => 'approved']);
            return back()->with('success', "Request fully approved.");
        }

        // Find the user assigned to that next approval level
        $nextApprover = $nextLevel->users()->first();

        if (!$nextApprover) {
            return back()->with('error', "No approver found for next level!");
        }

        // Create next pending approval row
        Approval::create([
            'request_id'  => $request->id,
            'approver_id' => $nextApprover->id,
            'level'       => $nextSequence,
            'status'      => 'pending',
        ]);

        return back()->with('success', "Level approved. Moved to next approver.");
    }
}








// namespace App\Http\Controllers;

// use App\Models\Approval;
// use App\Models\ApprovalLevel;
// use Illuminate\Http\Request;
// use App\Models\RequestModel; 
// use Illuminate\Support\Facades\Auth;

// class ApprovalController extends Controller
// {
//     /**
//      * Show all approvals for a given request
//      */
//     public function index()
// {
    
//     $approvals = Approval::with('request')->latest()->get();

//     return view('approvals.index', compact('approvals'));
// }


//     /**
//      * Show the form for creating a new approval for a given request
//      */
//     public function create($requestId)
//     {
//         $request = RequestModel::findOrFail($requestId);
//         return view('approvals.create', compact('request'));
//     }

//     /**
//      * Store a new approval for a request
//      */
//     public function store(Request $req, $requestId)
//     {
//         $req->validate([
//             'status'   => 'required|in:approve,reject',
//             'comments' => 'nullable|string|max:500',
//         ]);

//         Approval::create([
//             'request_id'  => $requestId,
//             'approver_id' => auth()->id(),
//             'status'      => $req->status,
//             'comments'    => $req->comments,
//         ]);

//         return redirect()
//             ->route('approvals.index', $requestId)
//             ->with('success', 'Approval submitted successfully.');
//     }
// }
