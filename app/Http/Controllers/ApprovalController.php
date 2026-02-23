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
    public function index() {
        $pendingApprovals = Approval::with('request.requestor')
            ->where('approver_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('approvals.index', compact('pendingApprovals'));
    }

    public function updateStatus(Request $req, $approvalId) {
        $user = Auth::user();
        
        // 1. Query with Admin Bypass
        $query = Approval::where('id', $approvalId)->where('status', 'pending');
        
        // Yahan ek choti si galti thi aapke logic mein (|| operator ki wajah se)
        // Ise aise likhein: Agar user admin nahi hai, tabhi approver_id check karein
        if (!$user->hasRole('super-admin') && !$user->hasRole('Admin')) {
            $query->where('approver_id', $user->id);
        }

        $approval = $query->first();
        if (!$approval) {
            return back()->with('error', 'Unauthorized or already processed.');
        }

        $request = $approval->request;
        $currentSequence = $approval->level;

        $req->validate([
            'status' => 'required|in:approved,rejected',
            'comments' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $approval->update([
                'status'   => $req->status,
                'comments' => $req->comments,
                'approver_id' => $user->id,
            ]);

            $request->update(['comments' => $req->comments]);

            // --- REJECTION LOGIC ---
            if ($req->status === 'rejected') {
                if ($currentSequence > 1) {
                    $previousLevel = $currentSequence - 1;
                    $request->update(['status' => 'need revision', 'current_level' => $previousLevel]);
                    
                    Approval::where('request_id', $request->id)->where('level', $previousLevel)
                        ->update([
                            'status' => 'pending',
                            'comments' => 'Returned for revision by ' . $user->name . ': ' . $req->comments
                        ]);

                    DB::commit();
                    return back()->with('success', "Sent back to Level $previousLevel.");
                } else {
                    $request->update(['status' => 'rejected']);
                    DB::commit();
                    return back()->with('success', 'Request fully rejected.');
                }
            }

            // --- APPROVAL LOGIC (Admin Bypass) ---
            if ($user->hasRole('super-admin') || $user->hasRole('Admin')) {
                // Agar Admin approve kare, toh workflow khatam!
                $request->update(['status' => 'approved']);
                DB::commit();
                return back()->with('success', 'Request fully approved by Admin (Workflow Bypassed).');
            } else {
                // Agar normal user approve kare, toh agle level par bhejein
                $response = $this->moveToNextLevel($request, $currentSequence);
                DB::commit();
                return $response;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function moveToNextLevel($request, $currentSequence) {
        $nextSequence = $currentSequence + 1;
        $nextLevel = ApprovalLevel::where('sequence', $nextSequence)->first();

        if (!$nextLevel) {
            $request->update(['status' => 'approved']);
            return back()->with('success', "Request fully approved!");
        }

        $nextApprover = $nextLevel->users()->first();

        if (!$nextApprover) {
            $request->update(['status' => 'Needs Approver', 'current_level' => $nextSequence]);
            return back()->with('warning', "Level $nextSequence has no user assigned.");
        }

        // SKIP LOGIC if next approver is the requestor
        if ($nextApprover->id === $request->requestor_id) {
            Approval::create([
                'request_id'  => $request->id,
                'approver_id' => $nextApprover->id,
                'level'       => $nextSequence,
                'status'      => 'approved',
                'comments'    => 'System: Auto-approved (Requestor match).'
            ]);
            return $this->moveToNextLevel($request, $nextSequence);
        }

        // Standard Move
        $request->update(['status' => 'pending', 'current_level' => $nextSequence]);
        Approval::create([
            'request_id'  => $request->id,
            'approver_id' => $nextApprover->id,
            'level'       => $nextSequence,
            'status'      => 'pending',
        ]);

        $this->sendNotification($nextApprover->id, "New Approval", "Request #$request->id is at your level.");
        return back()->with('success', "Moved to Level $nextSequence.");
    }

    private function sendNotification($userId, $title, $message, $role = null) {
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