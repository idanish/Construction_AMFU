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
    // public function updateStatus(Request $req, $approvalId)
    // {
    //     $req->validate([
    //         'status'   => 'required|in:approved,rejected',
    //         'comments' => 'nullable|string|max:500',
    //     ]);

    //     $approval = Approval::with('request')->findOrFail($approvalId);

    //     // --- Security Check: Only correct approver can approve ---
    //     if ($approval->approver_id !== Auth::id()) {
    //         return back()->with('error', "You are not authorized for this approval level.");
    //     }

    //     $request = $approval->request; // Request object
    //     $currentSequence = $approval->level; // Current sequence

    //     // Update current approval level
    //     $approval->update([
    //         'status'   => $req->status,
    //         'comments' => $req->comments,
    //     ]);

    //     // --- HANDLE REJECTION ---
    //     if ($req->status === 'rejected') {
    //         $previousSequence = $currentSequence - 1;

    //         if ($previousSequence >= 1) {
    //             // Send back to previous level for revision
    //             $request->update([
    //                 'status' => 'need revision',  // Match enum value
    //                 'current_level' => $previousSequence
    //             ]);

    //             return back()->with('warning', "Request rejected. Sent back to Level {$previousSequence} for revision.");
    //         } else {
    //             // Level 1 rejection = Final Rejection
    //             // Use 0 instead of null to avoid constraint violation
    //             $request->update([
    //                 'status' => 'rejected', 
    //                 'current_level' => 0
    //             ]);

    //             return back()->with('danger', "Request permanently rejected.");
    //         }
    //     }

        public function updateStatus(Request $req, $approvalId)
    {
        $req->validate([
            'status'   => 'required|in:approved,rejected',
            'comments' => 'nullable|string|max:500',
        ]);
    
        $approval = Approval::with('request')->findOrFail($approvalId);
    
        // --- Security Check ---
        if ($approval->approver_id !== Auth::id()) {
            return back()->with('error', "You are not authorized for this approval level.");
        }
    
        $request = $approval->request;
        $currentSequence = $approval->level;
    
        // 1. Current Step ko update karein (Approved/Rejected)
        $approval->update([
            'status'   => $req->status,
            'comments' => $req->comments,
        ]);
    
        // === CASE 1: AGAR APPROVER REJECT KARE (Back to Review) ===
        if ($req->status === 'rejected') {
            $previousSequence = $currentSequence - 1;
    
            if ($previousSequence >= 1) {
                // Request ka status aur level update karein
                $request->update([
                    'status' => 'need revision',
                    'current_level' => $previousSequence
                ]);
    
                // Pichle level ke approver ko dhoondein
                $prevLevel = ApprovalLevel::where('department_id', $request->department_id)
                    ->where('sequence', $previousSequence)
                    ->first();
                
                $prevApproverId = optional($prevLevel->users()->first())->id;
    
                if ($prevApproverId) {
                    // NAYA Pending record banayein taake pichle bande ko action button dikhe
                    Approval::create([
                        'request_id'  => $request->id,
                        'approver_id' => $prevApproverId,
                        'level'       => $previousSequence,
                        'status'      => 'pending',
                        'comments'    => 'Sent back for review by Level ' . $currentSequence . ': ' . $req->comments
                    ]);
                }
    
                return back()->with('warning', "Request rejected. Sent back to Level {$previousSequence} for revision.");
            } else {
                // Level 1 rejection = Final Rejection (0 is used to show it's off-workflow)
                $request->update([
                    'status' => 'rejected', 
                    'current_level' => 0
                ]);
    
                return back()->with('danger', "Request permanently rejected.");
            }
        }
    
        // === CASE 2: AGAR APPROVER APPROVE KARE (Move Forward) ===
        
        // Check if it's a Private Request (Private requests have no next levels)
        if ($request->type === 'private') {
            $request->update(['status' => 'approved']);
            return back()->with('success', "Private request fully approved!");
        }
    
        $nextSequence = $currentSequence + 1;
    
        // Agla level dhoondein
        $nextLevel = ApprovalLevel::where('department_id', $request->department_id)
            ->where('sequence', $nextSequence)
            ->first();
    
        // Agar agla level nahi hai toh "Final Approval"
        if (!$nextLevel) {
            $request->update([
                'status' => 'approved', 
                'current_level' => $currentSequence // Current level hi highest hai
            ]);
    
            return back()->with('success', "Request fully approved!");
        }
    
        // Agla approver dhoondein
        $nextApprover = $nextLevel->users()->first();
    
        if (!$nextApprover) {
            // Level exist karta hai magar user nahi assign
            $request->update([
                'status' => 'pending', // Yahan aap 'Needs Approver' bhi likh sakte hain
                'current_level' => $nextSequence
            ]);
            return back()->with('error', "Approved! But no approver found for Level {$nextSequence}.");
        }
    
        // Request ko agle level par move karein
        $request->update([
            'status' => 'pending',
            'current_level' => $nextSequence
        ]);
    
        // Agle level ke liye pending entry banayein
        Approval::create([
            'request_id'  => $request->id,
            'approver_id' => $nextApprover->id,
            'level'       => $nextSequence,
            'status'      => 'pending',
        ]);
    
        return back()->with('success', "Level {$currentSequence} approved. Moved to Level {$nextSequence}.");
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
