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

        $userLevelSequence = optional($user->approvalLevel)->sequence;

        $pendingApprovals = Approval::with('request')
        ->where('approver_id', $user->id)
        ->where('status', 'pending')
        ->latest()
        ->get();

        return view('approvals.index', compact('pendingApprovals'));
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
    $request = $approval->request; // Request object ko define karein
    $currentSequence = $approval->level; // Current sequence ko define karein
    
        // Update current approval level
        $approval->update([
            'status'   => $req->status,
            'comments' => $req->comments,
        ]);

        // If rejected → request rejected & no more levels required
        if ($req->status === 'rejected') {
            
            $previousSequence = $currentSequence - 1;

            if ($previousSequence >= 1) {
                // Agar Level 1 se bada hai to pichhle level par wapas bhejo
                
                // Request ka status aur current_level update karna
                $request->update([
                    'status' => 'Needs Revision', 
                    'current_level' => $previousSequence
                ]);
        
                
                return back()->with('warning', "Request rejected. Sent back to Requestor for revision (Level {$previousSequence}).");
            } else {
                 // Level 1 par rejection = Final Rejection
                 $request->update(['status' => 'rejected', 'current_level' => null]);
                 return back()->with('danger', "Request permanently rejected.");
            }
}

// --- 3. Handle Approval (Next Level Par Bhejna) ---

    $nextSequence = $currentSequence + 1;

    // Next approval level dhoondhna
    $nextLevel = ApprovalLevel::where('department_id', $request->department_id)
      ->where('sequence', $nextSequence)
      ->first();

    // Final Approval
    if (!$nextLevel) {
      $request->update(['status' => 'approved', 'current_level' => null]);
      return back()->with('success', "Request fully approved!");
    }

    // Next Approver dhoondhna
    $nextApprover = $nextLevel->users()->first();

    if (!$nextApprover) {
      // If next level exists but no user assigned:
      $request->update(['status' => 'Needs Approver', 'current_level' => $nextSequence]);
      return back()->with('error', "Level approved. Error: No approver found for Level {$nextSequence}!");
    }

        // Request ka current level update karna
        $request->update(['current_level' => $nextSequence]);

    // Next pending approval row create karna
    Approval::create([
      'request_id' => $request->id,
      'approver_id' => $nextApprover->id,
      'level'    => $nextSequence,
      'status'   => 'pending',
    ]);

        return back()->with('success', "Level approved. Moved to next approver.");
    }
}