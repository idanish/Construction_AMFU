<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RequestModel;
use App\Models\User;
use App\Models\Department;

class TestApprovalWorkflow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:test-approval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the complete approval workflow and email notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🧪 Testing Approval Workflow...\n");

        // Get test requester
        $requester = User::where('email', 'requester@test.com')->first();
        if (!$requester) {
            $this->error("❌ Test requester not found! Run: php artisan db:seed --class=ApprovalWorkflowTestSeeder");
            return 1;
        }

        // Get test department
        $department = Department::first();
        if (!$department) {
            $this->error("❌ No department found!");
            return 1;
        }

        $this->info("✓ Using Requester: {$requester->name} ({$requester->email})");
        $this->info("✓ Using Department: {$department->name}\n");

        // Create test request
        $this->info("📝 Creating test request...");
        $request = RequestModel::create([
            'requestor_id' => $requester->id,
            'department_id' => $department->id,
            'title' => 'Test Approval Workflow Request',
            'description' => 'This is a test request to verify email notifications in the approval workflow.',
            'amount' => 50000,
            'status' => 'Pending',
            'current_approval_step' => 'PM'
        ]);

        $this->info("✓ Request created (ID: {$request->id})\n");

        // Trigger approval creation (which sends emails)
        $this->info("📧 Creating approvals and sending emails...");
        \App\Http\Controllers\ApprovalController::createApprovalsForRequest($request);

        $approvals = $request->approvals()->get();
        $this->info("✓ {$approvals->count()} approvals created\n");

        $this->info("📋 Approval Chain:");
        foreach ($approvals as $approval) {
            $approver = $approval->approver;
            $this->info("  - {$approval->approval_step}: {$approver->name} ({$approver->email})");
        }

        $this->info("\n✅ Test Complete!");
        $this->info("Check logs: storage/logs/laravel.log");
        $this->info("Look for email notifications sent to each approver.\n");

        return 0;
    }
}
