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

        $request = $approval->request; // Request object
        $currentSequence = $approval->level; // Current sequence

        // Update current approval level
        $approval->update([
            'status'   => $req->status,
            'comments' => $req->comments,
        ]);

        // --- HANDLE REJECTION ---
        if ($req->status === 'rejected') {
            $previousSequence = $currentSequence - 1;

            if ($previousSequence >= 1) {
                // Send back to previous level for revision
                $request->update([
                    'status' => 'need revision',  // Match enum value
                    'current_level' => $previousSequence
                ]);

                return back()->with('warning', "Request rejected. Sent back to Level {$previousSequence} for revision.");
            } else {
                // Level 1 rejection = Final Rejection
                // Use 0 instead of null to avoid constraint violation
                $request->update([
                    'status' => 'rejected', 
                    'current_level' => 0
                ]);

                return back()->with('danger', "Request permanently rejected.");
            }
        }

        // --- HANDLE APPROVAL (Move to Next Level) ---
        $nextSequence = $currentSequence + 1;

        // Find next approval level
        $nextLevel = ApprovalLevel::where('department_id', $request->department_id)
            ->where('sequence', $nextSequence)
            ->first();

        // --- FINAL APPROVAL (No more levels) ---
        if (!$nextLevel) {
            // Get the highest level for this department
            $highestLevel = ApprovalLevel::where('department_id', $request->department_id)
                ->max('sequence');

            // Use highest level instead of null to avoid constraint violation
            $request->update([
                'status' => 'approved', 
                'current_level' => $highestLevel ?? $currentSequence
            ]);

            return back()->with('success', "Request fully approved!");
        }

        // --- FIND NEXT APPROVER ---
        $nextApprover = $nextLevel->users()->first();

        if (!$nextApprover) {
            // If next level exists but no user assigned
            $request->update([
                'status' => 'pending',
                'current_level' => $nextSequence
            ]);

            return back()->with('error', "Level {$currentSequence} approved but no approver found for Level {$nextSequence}!");
        }

        // Update request to next level
        $request->update([
            'status' => 'pending',
            'current_level' => $nextSequence
        ]);

        // Create next pending approval
        Approval::create([
            'request_id'  => $request->id,
            'approver_id' => $nextApprover->id,
            'level'       => $nextSequence,
            'status'      => 'pending',
        ]);

        return back()->with('success', "Level {$currentSequence} approved. Moved to Level {$nextSequence}.");
    }
}