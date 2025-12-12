<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call roles and permissions seeder
        $this->call(RolesAndPermissionsSeeder::class);
        
        $adminDepartment = Department::updateOrCreate(['name' => 'Admin Department'],);

        // Default Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'status' => '1',
                'department_id' => $adminDepartment->id,
            ]
        );
        $admin->assignRole('Admin');


        //  Default Department
        Department::updateOrCreate(['name' => 'HR Department']);
        Department::updateOrCreate(['name' => 'Project Management Department']);
        Department::updateOrCreate(['name' => 'Finance & Commercial Department']);
        Department::updateOrCreate(['name' => 'Security & Administration Department']);


        

    }
}