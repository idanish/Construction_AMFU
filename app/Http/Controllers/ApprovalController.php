<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\ApprovalLevel;
use App\Models\Notification;
use App\Models\RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingApprovals = Approval::with('request.requestor')
            ->where('approver_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('approvals.index', compact('pendingApprovals'));
    }

    public function updateStatus(Request $req, $approvalId)
    {
        // 1. Validation
        $req->validate([
            'status' => 'required|in:approved,rejected',
            'comments' => 'nullable|string|max:500',
        ]);

        // 2. Approval aur Request ko dhoondein (Pehle inhein define karna zaroori hai)
        $approval = Approval::where('id', $approvalId)
                            ->where('approver_id', Auth::id())
                            ->where('status', 'pending')
                            ->firstOrFail();

        $request = $approval->request; // Ab $request define ho gaya
        $currentSequence = $approval->level;

        DB::beginTransaction();
        try {
            // 3. Comments ko sync karein (Dono tables mein)
            $approval->update([
                'status'   => $req->status,
                'comments' => $req->comments,
            ]);

            // Main Request table ke comments update karein
            $request->update([
                'comments' => $req->comments
            ]);

            // === CASE 1: REJECTED / SEND BACK ===
            if ($req->status === 'rejected') {
        if ($currentSequence > 1) {
            $previousLevel = $currentSequence - 1;
            
            $request->update([
                'status' => 'need revision', 
                'current_level' => $previousLevel,
                'comments' => "Sent back from Level {$currentSequence}: " . $req->comments
            ]);

            // Pichlay level ki approval entry dhoondein
            $prevApproval = Approval::where('request_id', $request->id)
                                    ->where('level', $previousLevel)
                                    ->first();

            if ($prevApproval) {
                // 1. Notification to the Previous Approver (User B)
                $this->sendNotification(
                    $prevApproval->approver_id, 
                    "Request Sent Back for Revision", 
                    "Request #{$request->id} has been sent back to you from Level {$currentSequence}."
                );

                // 2. Notification to the Original Requestor (User A)
                $this->sendNotification(
                    $request->requestor_id, 
                    "Your Request Needs Revision", 
                    "Your request #{$request->id} has been sent back to Level {$previousLevel} for changes."
                );

                $prevApproval->update(['status' => 'pending']);
            }

            DB::commit();
            return back()->with('success', "Request sent back to Level {$previousLevel}. Notifications sent.");
            } 
            else {
                // Level 1 rejection (Back to Requestor)
                $request->update([
                    'status' => 'rejected',
                    'comments' => "Rejected at Level 1: " . $req->comments
                ]);

                $this->sendNotification($request->requestor_id, "Request Rejected", "Your request #{$request->id} was rejected at Level 1.");
                
                DB::commit();
                return back()->with('success', 'Request has been rejected.');
            }
        }

            // === CASE 2: APPROVED ===
            $response = $this->moveToNextLevel($request, $currentSequence);
            
            DB::commit();
            return $response;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Approval Error: " . $e->getMessage());
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function moveToNextLevel($request, $currentSequence)
    {
        // Private Request logic
        if ($request->type === 'private') {
            $request->update(['status' => 'approved']);

            // Notification for Private Approval
            $this->sendNotification(
                $request->requestor_id, 
                "Request Approved", 
                "Your private request '{$request->title}' has been fully APPROVED."
            );

            return back()->with('success', "Private request fully approved!");
        }

        $nextSequence = $currentSequence + 1;
        $nextLevel = ApprovalLevel::where('department_id', $request->department_id)
            ->where('sequence', $nextSequence)
            ->first();

        // 1. Agar agla level nahi hai (Final Approval)
        if (!$nextLevel) {
            $request->update(['status' => 'approved', 'current_level' => $currentSequence]);
            
            // Notify Requestor
            $this->sendNotification(
                $request->requestor_id, 
                "Request Approved", 
                "Your request '{$request->title}' has been fully APPROVED."
            );
            
            return back()->with('success', "Request fully approved!");
        }

        $nextApprover = $nextLevel->users()->first();

        // 2. Agar level hai magar user nahi hai (Needs Admin Attention)
        if (!$nextApprover) {
            $request->update(['status' => 'Needs Approver', 'current_level' => $nextSequence]);
            
            // Notification for Admin or Role (Agar aapka system role support karta hai)
            $this->sendNotification(
                null, 
                "Approver Missing", 
                "Level {$nextSequence} has no assigned user for request: '{$request->title}'.",
                "super-admin" // Aapka role system
            );

            return back()->with('warning', "Approved, but Level {$nextSequence} has no assigned user. Admin notified.");
        }

        // --- SELF-APPROVAL SKIP LOGIC ---
        if ($nextApprover->id === $request->requestor_id) {
            Approval::create([
                'request_id'  => $request->id,
                'approver_id' => $nextApprover->id,
                'level'       => $nextSequence,
                'status'      => 'approved',
                'comments'    => 'System: Auto-approved (Requestor is the Approver at Level '.$nextSequence.').'
            ]);

            // Note: Skip hone par notification ki zaroorat nahi kyunke ye foran agle level par ja raha hai
            return $this->moveToNextLevel($request, $nextSequence);
        }

        // 3. Standard Next Step (Move to Next Person)
        $request->update(['status' => 'pending', 'current_level' => $nextSequence]);
        
        Approval::create([
            'request_id'  => $request->id,
            'approver_id' => $nextApprover->id,
            'level'       => $nextSequence,
            'status'      => 'pending',
        ]);

        // Notify Next Approver
        $this->sendNotification(
            $nextApprover->id, 
            "New Approval Required", 
            "You have a new pending request: '{$request->title}' at Level {$nextSequence}."
        );

        return back()->with('success', "Approved and moved to Level {$nextSequence}.");
    }

    private function sendNotification($userId, $title, $message, $role = null)
    {
        if ($userId || $role) {
            \App\Models\Notification::create([
                'user_id' => $userId,
                'role'    => $role,
                'type'    => 'approve', // Mandatory field as per your migration
                'message' => $title . ": " . $message, // Title aur message ko merge kar diya
                'is_read' => false,
                'transaction_no' => 0, // Default value as per migration
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
            \Log::error("Mail Error in Notification: " . $e->getMessage());
        }
    }
}