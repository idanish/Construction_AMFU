<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ApprovalWorkflowTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $roles = ['PM Manager', 'PMO', 'FCO', 'CSO', 'Admin'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Get FIRST department or create one
        $department = Department::first();
        if (!$department) {
            // If no department exists, create one
            $department = Department::create([
                'name' => 'Test Department'
            ]);
        }

        // Create approvers for each role in the same department
        $approversData = [
            ['name' => 'PM Manager User', 'email' => 'pm@test.com', 'role' => 'PM Manager'],
            ['name' => 'PMO User', 'email' => 'pmo@test.com', 'role' => 'PMO'],
            ['name' => 'FCO User', 'email' => 'fco@test.com', 'role' => 'FCO'],
            ['name' => 'CSO User', 'email' => 'cso@test.com', 'role' => 'CSO'],
        ];

        foreach ($approversData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'username' => str_replace('@', '_', $data['email']),
                    'password' => Hash::make('password123'),
                    'department_id' => $department->id,
                    'status' => 1
                ]
            );

            // Assign role if not already assigned
            if (!$user->hasRole($data['role'])) {
                $user->assignRole($data['role']);
            }

            echo "✓ Created/Updated: {$data['name']} ({$data['email']}) with role {$data['role']}\n";
        }

        // Create or update Admin user (globally, not department-specific)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin_test',
                'password' => Hash::make('password123'),
                'department_id' => $department->id,
                'status' => 1
            ]
        );

        if (!$adminUser->hasRole('Admin')) {
            $adminUser->assignRole('Admin');
        }

        echo "✓ Created/Updated: Admin User (admin@test.com) with role Admin\n";

        // Create a test requester user
        $requester = User::firstOrCreate(
            ['email' => 'requester@test.com'],
            [
                'name' => 'Test Requester',
                'username' => 'requester_test',
                'password' => Hash::make('password123'),
                'department_id' => $department->id,
                'status' => 1
            ]
        );

        echo "✓ Created/Updated: Test Requester (requester@test.com)\n";

        echo "\n✅ Approval Workflow Test Data Ready!\n";
        echo "Test Flow:\n";
        echo "1. Login as: requester@test.com (password: password123)\n";
        echo "2. Create a Request\n";
        echo "3. Approvals will be sent to:\n";
        echo "   - PM Manager: pm@test.com\n";
        echo "   - PMO: pmo@test.com\n";
        echo "   - FCO: fco@test.com\n";
        echo "   - CSO: cso@test.com\n";
        echo "   - Admin: admin@test.com\n";
        echo "4. All emails will go to logs (check storage/logs/laravel.log)\n";
    }
}
