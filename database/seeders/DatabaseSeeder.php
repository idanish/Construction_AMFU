<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Budget;
use App\Models\Procurement;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call roles and permissions seeder
        $this->call(RolesAndPermissionsSeeder::class);

        // Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'super@example.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('12345678'),
                'status' => '1',
            ]
        );
        $superAdmin->assignRole('Super Admin');
        
        // Default Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'status' => '1',
            ]
        );
        $admin->assignRole('Admin');

        // Default User (Example: PM/User)
        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'username' => 'userpm',
                'password' => Hash::make('12345678'),
            ]
        );
        $user->assignRole('PM');

        //  Default Department
        Department::updateOrCreate(
            ['name' => 'Project Management Department'],
            ['transaction_no' => 1],
        );
        
        Department::updateOrCreate(
            ['name' => 'Finance & Commercial Department'],
            ['transaction_no' => 2],
        );

        Department::updateOrCreate(
            ['name' => 'Security & Administration Department'],
            ['transaction_no' => 3]
        );

        // Default Department
        $department = Department::updateOrCreate(
            ['name' => 'Project Management Department'],
            ['transaction_no' => 1],
        );

        // Default Budget
        Budget::updateOrCreate(
            ['title' => 'Default Budget'],
            [
                'department_id' => $department->id, 
                'allocated' => 10000,
                'spent' => 0,
                'balance' => 10000,
                'year' => 2025,
                'notes' => 'This is a default budget.',
                'transaction_no' => 1
            ]
        );

        

    }
}