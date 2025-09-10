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

        // ✅ Default Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
            ]
        );
        $admin->assignRole('Admin');

        // ✅ Default User (Example: PM/User)
        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'password' => Hash::make('12345678'),
            ]
        );
        $user->assignRole('PM'); // Ya jo bhi role ho

        // ✅ Default Department
        Department::updateOrCreate(
            ['name' => 'Default Department'],
            ['transaction_no' => 1]
        );

        // ✅ Default Department
$department = Department::updateOrCreate(
    ['name' => 'Default Department'],
    ['transaction_no' => 1]
);

// ✅ Default Budget
Budget::updateOrCreate(
    ['title' => 'Default Budget'],
    [
        'department_id' => $department->id, 
        'allocated' => 10000,
        'spent' => 0,
        'balance' => 10000,
        'transaction_no' => 1
    ]
);


        // ✅ Default Procurement
        Procurement::updateOrCreate(
    ['title' => 'Default Procurement'],
    [
        'transaction_no' => 1,
        'department_id' => 1 // yahan koi valid department ka ID daalo
    ]
);

        // ... baki tables bhi isi style me add karo (Reports & Audit skip)
    }
}