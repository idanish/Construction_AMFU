<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use App\Models\RequestModel;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalNotification;
use App\Mail\ApprovalCompletedNotification;
use App\Mail\ApprovalRejectedNotification;
use App\Mail\NewSubmissionNotification;
use App\Mail\AdminNotification;

class ApprovalController extends Controller
{
    // Define approval steps in order
    private $steps = [
        ['step' => 'PM', 'role' => 'PM Manager', 'order' => 1],
        ['step' => 'PMO', 'role' => 'PMO', 'order' => 2],
        ['step' => 'FCO', 'role' => 'FCO', 'order' => 3],
        ['step' => 'CSO', 'role' => 'CSO', 'order' => 4],
        ['step' => 'Admin', 'role' => 'Admin', 'order' => 5],
    ];

    public function index()
    {
        $userId = Auth::id();
        
        // Get pending approvals for this user across all their assigned roles
        $pendingApprovals = Approval::with(['request.requestor', 'request.department'])
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->orderBy('step_order', 'asc')
            ->get();

        return view('approvals.index', compact('pendingApprovals'));
    }

    public function approve(Request $request, $id)
    {
        $approval = Approval::findOrFail($id);

        // Authorization check
        if ($approval->approver_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to approve this request.');
        }

        // Ensure this is a pending approval
        if ($approval->status !== 'pending') {
            return redirect()->back()->with('error', 'This approval has already been acted upon.');
        }

        $approval->status = 'approved';
        $approval->note = $request->input('note', '');
        $approval->acted_at = now();
        $approval->save();

        // Get the approvable model (can be Request, Invoice, Procurement, Budget, or Payment)
        $approvable = $approval->approvable ?? $approval->request;

        // Find the next pending approval for this approvable
        $nextApproval = $approvable->approvals()
            ->where('status', 'pending')
            ->orderBy('step_order', 'asc')
            ->first();

        if ($nextApproval) {
            // Move to next step
            if (method_exists($approvable, 'update')) {
                $approvable->update(['current_approval_step' => $nextApproval->approval_step]);
            }

            // Notify next approver with professional email
            try {
                $nextUser = User::find($nextApproval->approver_id);
                $modelType = class_basename(get_class($approvable));
                
                // Create notification details
                $modelDetails = [
                    'ID' => $approvable->id,
                    'Status' => $approvable->status ?? 'pending',
                ];
                
                // Add relevant details based on model type
                if ($modelType === 'RequestModel') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Department'] = $approvable->department?->name ?? 'N/A';
                } elseif ($modelType === 'Invoice') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Invoice No'] = $approvable->invoice_no ?? 'N/A';
                } elseif ($modelType === 'Procurement') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Vendor'] = $approvable->vendor_name ?? 'N/A';
                } elseif ($modelType === 'Budget') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Title'] = $approvable->title ?? 'N/A';
                } elseif ($modelType === 'Payment') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Invoice'] = $approvable->invoice_id ?? 'N/A';
                }
                
                createNotification(null, "{$modelType} (#{$approvable->id}) is waiting for your {$nextApproval->approval_step} approval.", $nextApproval->approver_id, 'approval');
                
                if ($nextUser && $nextUser->email) {
                    Mail::to($nextUser->email)->send(new ApprovalNotification(
                        $modelType,
                        $approvable->id,
                        $nextApproval->approval_step,
                        $modelDetails,
                        null // Add URL if you have routes for approval
                    ));
                }
                
                // Notify admin (use ADMIN_EMAIL if set, otherwise MAIL_FROM_ADDRESS)
                try {
                    $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
                    if ($adminEmail) {
                        Mail::to($adminEmail)->send(new AdminNotification(
                            $modelType,
                            $approvable->id,
                            'Approval Progress',
                            array_merge($modelDetails, [
                                'Approver' => Auth::user()?->name ?? 'System',
                                'Step' => $nextApproval->approval_step
                            ])
                        ));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to notify admin: ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                Log::error('Failed to notify next approver: ' . $e->getMessage());
            }
        } else {
            // All approvals done - approvable is fully approved
            if (method_exists($approvable, 'update')) {
                $approvable->update(['status' => 'approved', 'approved_at' => now()]);
            }

            // Notify requester/creator that item is fully approved
            try {
                $modelType = class_basename(get_class($approvable));
                
                // Get creator details
                $creator = null;
                if ($modelType === 'RequestModel') {
                    $creator = $approvable->requestor;
                } else {
                    $creator = Auth::user();
                }
                
                // Create model details
                $modelDetails = [
                    'ID' => $approvable->id,
                    'Status' => 'Approved',
                ];
                
                if ($modelType === 'RequestModel') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Department'] = $approvable->department?->name ?? 'N/A';
                } elseif ($modelType === 'Invoice') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Invoice No'] = $approvable->invoice_no ?? 'N/A';
                } elseif ($modelType === 'Procurement') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Vendor'] = $approvable->vendor_name ?? 'N/A';
                } elseif ($modelType === 'Budget') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Title'] = $approvable->title ?? 'N/A';
                } elseif ($modelType === 'Payment') {
                    $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                    $modelDetails['Invoice'] = $approvable->invoice_id ?? 'N/A';
                }
                
                createNotification(null, "Your {$modelType} (#{$approvable->id}) has been fully approved.", $creator?->id ?? Auth::id(), 'approval');
                
                if ($creator && $creator->email) {
                    Mail::to($creator->email)->send(new ApprovalCompletedNotification(
                        $modelType,
                        $approvable->id,
                        $modelDetails
                    ));
                }
                
                // Notify admin about full approval
                try {
                    $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
                    if ($adminEmail) {
                        Mail::to($adminEmail)->send(new AdminNotification(
                            $modelType,
                            $approvable->id,
                            'Fully Approved',
                            array_merge($modelDetails, [
                                'Approved By' => Auth::user()?->name ?? 'System',
                                'Approved At' => now()->format('Y-m-d H:i:s')
                            ])
                        ));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to notify admin: ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                Log::error('Failed to notify creator of full approval: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Approval granted and moved to next step.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['revert_reason' => 'required|string|min:5']);

        $approval = Approval::findOrFail($id);

        // Authorization check
        if ($approval->approver_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to reject this request.');
        }

        // Ensure this is a pending approval
        if ($approval->status !== 'pending') {
            return redirect()->back()->with('error', 'This approval has already been acted upon.');
        }

        $approval->status = 'rejected';
        $approval->revert_reason = $request->input('revert_reason');
        $approval->note = 'Rejected at ' . $approval->approval_step . ' step';
        $approval->acted_at = now();
        $approval->save();

        // Get the approvable model
        $approvable = $approval->approvable ?? $approval->request;
        $modelType = class_basename(get_class($approvable));

        // Update approvable status to reverted
        if (method_exists($approvable, 'update')) {
            $approvable->update([
                'status' => 'reverted',
                'revert_reason' => "Rejected at {$approval->approval_step} step by " . Auth::user()?->name . ": {$request->input('revert_reason')}",
                'current_approval_step' => 'PM', // Reset to PM step
            ]);
        }

        // Reset all approvals to pending for resubmission
        $approvable->approvals()->update(['status' => 'pending', 'note' => null, 'acted_at' => null, 'revert_reason' => null]);

        // Get creator
        $creator = null;
        if ($modelType === 'RequestModel') {
            $creator = $approvable->requestor;
        } else {
            $creator = Auth::user();
        }

        // Notify creator/requestor with rejection reason
        try {
            createNotification(null, "Your {$modelType} (#{$approvable->id}) was reverted at {$approval->approval_step} step. Reason: {$request->input('revert_reason')}", $creator?->id ?? Auth::id(), 'approval');
            
            // Create model details
            $modelDetails = [
                'ID' => $approvable->id,
                'Status' => 'Reverted',
            ];
            
            if ($modelType === 'RequestModel') {
                $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                $modelDetails['Department'] = $approvable->department?->name ?? 'N/A';
            } elseif ($modelType === 'Invoice') {
                $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                $modelDetails['Invoice No'] = $approvable->invoice_no ?? 'N/A';
            } elseif ($modelType === 'Procurement') {
                $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                $modelDetails['Vendor'] = $approvable->vendor_name ?? 'N/A';
            } elseif ($modelType === 'Budget') {
                $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                $modelDetails['Title'] = $approvable->title ?? 'N/A';
            } elseif ($modelType === 'Payment') {
                $modelDetails['Amount'] = $approvable->amount ?? 'N/A';
                $modelDetails['Invoice'] = $approvable->invoice_id ?? 'N/A';
            }
            
            if ($creator && $creator->email) {
                Mail::to($creator->email)->send(new ApprovalRejectedNotification(
                    $modelType,
                    $approvable->id,
                    $approval->approval_step,
                    $request->input('revert_reason'),
                    Auth::user()?->name ?? 'System',
                    $modelDetails
                ));
            }
            
            // Notify admin about rejection
            try {
                $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new AdminNotification(
                        $modelType,
                        $approvable->id,
                        'Rejected',
                        array_merge($modelDetails, [
                            'Rejected By' => Auth::user()?->name ?? 'System',
                            'Reason' => $request->input('revert_reason'),
                            'Step' => $approval->approval_step
                        ])
                    ));
                }
            } catch (\Exception $e) {
                Log::error('Failed to notify admin: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify creator of rejection: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Item rejected. Creator has been notified and can resubmit.');
    }

    // Helper method to create approval rows for any approvable model (polymorphic)
    public static function createApprovalsForModel($model)
    {
        $steps = [
            ['step' => 'PM', 'role' => 'PM Manager', 'order' => 1],
            ['step' => 'PMO', 'role' => 'PMO', 'order' => 2],
            ['step' => 'FCO', 'role' => 'FCO', 'order' => 3],
            ['step' => 'CSO', 'role' => 'CSO', 'order' => 4],
            ['step' => 'Admin', 'role' => 'Admin', 'order' => 5],
        ];

        // Determine the model type and get department for role matching
        $approvableType = get_class($model);
        $departmentId = null;

        if (method_exists($model, 'department')) {
            $departmentId = $model->department_id;
        } elseif (property_exists($model, 'department_id')) {
            $departmentId = $model->department_id;
        }

        foreach ($steps as $step) {
            // Find a user with the required role in the same department or globally for Admin
            $query = User::whereHas('roles', function ($q) use ($step) {
                $q->where('name', $step['role']);
            });

            // For non-Admin roles, restrict to department
            if ($step['role'] !== 'Admin' && $departmentId) {
                $query->where('department_id', $departmentId);
            }

            $approver = $query->first();

            if ($approver) {
                Approval::create([
                    'approvable_type' => $approvableType,
                    'approvable_id' => $model->id,
                    'approver_id' => $approver->id,
                    'status' => 'pending',
                    'approval_step' => $step['step'],
                    'assigned_role' => $step['role'],
                    'step_order' => $step['order']
                ]);

                // Notify the first approver (PM) with new submission notification
                if ($step['order'] === 1) {
                    try {
                        $modelName = class_basename($approvableType);
                        
                        // Get submitter name
                        $submitterName = 'System';
                        if ($modelName === 'RequestModel' && $model->requestor) {
                            $submitterName = $model->requestor->name;
                        }
                        
                        // Create model details
                        $modelDetails = [
                            'ID' => $model->id,
                            'Status' => $model->status ?? 'pending',
                        ];
                        
                        if ($modelName === 'RequestModel') {
                            $modelDetails['Amount'] = $model->amount ?? 'N/A';
                            $modelDetails['Department'] = $model->department?->name ?? 'N/A';
                            $modelDetails['Title'] = $model->title ?? 'N/A';
                        } elseif ($modelName === 'Invoice') {
                            $modelDetails['Amount'] = $model->amount ?? 'N/A';
                            $modelDetails['Invoice No'] = $model->invoice_no ?? 'N/A';
                            $modelDetails['Vendor'] = $model->vendor_name ?? 'N/A';
                        } elseif ($modelName === 'Procurement') {
                            $modelDetails['Amount'] = $model->amount ?? 'N/A';
                            $modelDetails['Vendor'] = $model->vendor_name ?? 'N/A';
                            $modelDetails['Description'] = $model->description ?? 'N/A';
                        } elseif ($modelName === 'Budget') {
                            $modelDetails['Amount'] = $model->amount ?? 'N/A';
                            $modelDetails['Title'] = $model->title ?? 'N/A';
                            $modelDetails['Department'] = $model->department?->name ?? 'N/A';
                        } elseif ($modelName === 'Payment') {
                            $modelDetails['Amount'] = $model->amount ?? 'N/A';
                            $modelDetails['Invoice'] = $model->invoice_id ?? 'N/A';
                        }
                        
                        createNotification(null, "New {$modelName} (#{$model->id}) is waiting for your PM approval.", $approver->id, 'approval');
                        
                        if ($approver->email) {
                            Mail::to($approver->email)->send(new NewSubmissionNotification(
                                $modelName,
                                $model->id,
                                $submitterName,
                                $modelDetails
                            ));
                        }
                        
                        // Notify admin about new submission
                        try {
                            $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
                            if ($adminEmail) {
                                Mail::to($adminEmail)->send(new AdminNotification(
                                    $modelName,
                                    $model->id,
                                    'New Submission',
                                    array_merge($modelDetails, [
                                        'Submitted By' => $submitterName,
                                        'Submitted At' => now()->format('Y-m-d H:i:s')
                                    ])
                                ));
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to notify admin: ' . $e->getMessage());
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to notify PM or admin: ' . $e->getMessage());
                    }
                }
            }
        }
    }

    // Helper method to create approval rows for a request (legacy, uses polymorphic approach)
    public static function createApprovalsForRequest(RequestModel $request)
    {
        $steps = [
            ['step' => 'PM', 'role' => 'PM Manager', 'order' => 1],
            ['step' => 'PMO', 'role' => 'PMO', 'order' => 2],
            ['step' => 'FCO', 'role' => 'FCO', 'order' => 3],
            ['step' => 'CSO', 'role' => 'CSO', 'order' => 4],
            ['step' => 'Admin', 'role' => 'Admin', 'order' => 5],
        ];

        foreach ($steps as $step) {
            // Find a user with the required role in the same department or globally for Admin
            $query = User::whereHas('roles', function ($q) use ($step) {
                $q->where('name', $step['role']);
            });

            // For non-Admin roles, restrict to department
            if ($step['role'] !== 'Admin') {
                $query->where('department_id', $request->department_id);
            }

            $approver = $query->first();

            if ($approver) {
                Approval::create([
                    'request_id' => $request->id,
                    'approver_id' => $approver->id,
                    'status' => 'pending',
                    'approval_step' => $step['step'],
                    'assigned_role' => $step['role'],
                    'step_order' => $step['order']
                ]);

                // Notify the first approver (PM) with professional email
                if ($step['order'] === 1) {
                    try {
                        $modelDetails = [
                            'Title' => $request->title ?? 'N/A',
                            'Amount' => $request->amount ?? 'N/A',
                            'Department' => $request->department?->name ?? 'N/A',
                            'Description' => $request->description ?? 'N/A',
                        ];
                        
                        createNotification(null, "New request (#{$request->id}) from {$request->requestor->name} is waiting for your PM approval.", $approver->id, 'approval');
                        
                        if ($approver->email) {
                            Mail::to($approver->email)->send(new NewSubmissionNotification(
                                'Request',
                                $request->id,
                                $request->requestor->name,
                                $modelDetails
                            ));
                        }
                        
                        // Notify admin about new submission
                        try {
                            $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
                            if ($adminEmail) {
                                Mail::to($adminEmail)->send(new AdminNotification(
                                    'Request',
                                    $request->id,
                                    'New Submission',
                                    array_merge($modelDetails, [
                                        'Submitted By' => $request->requestor->name,
                                        'Submitted At' => now()->format('Y-m-d H:i:s')
                                    ])
                                ));
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to notify admin: ' . $e->getMessage());
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to notify PM or admin: ' . $e->getMessage());
                    }
                }
            }
        }
    }
}
