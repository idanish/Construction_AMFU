<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\ApprovalLevel;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles and Permissions Seeder ko call karna
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Data array: Ismein tamam users, unke departments aur levels ka data hai
        $usersData = [
            [
                'email' => 'admin@amfu.net',
                'username' => 'admin',
                'name' => 'Admin',
                'role' => 'Admin',
                'dept' => 'Admin Department',
                'level_name' => 'Level 1',
                'seq' => 1
            ],
            [
                'email' => 'sameh.kotb@amfu.net',
                'username' => 'sameh.kotb',
                'name' => 'sameh.kotb',
                'role' => 'Operation Manager',
                'dept' => 'IT Department',
                'level_name' => 'Level 1',
                'seq' => 1
            ],
            [
                'email' => 'tohamy@amfu.net',
                'username' => 'tohamy',
                'name' => 'tohamy',
                'role' => 'Procurement Finance Manager',
                'dept' => 'HR Department',
                'level_name' => 'Level 2',
                'seq' => 2
            ],
            [
                'email' => 'm.abukarroug@amfu.net',
                'username' => 'm.abukarroug',
                'name' => 'm.abukarroug',
                'role' => 'Monitor Manager',
                'dept' => 'Strategy and Finance Audit',
                'level_name' => 'Level 3',
                'seq' => 3
            ],
            [
                'email' => 'mohamedraouf@amfu.net',
                'username' => 'mohamedraouf',
                'name' => 'mohamedraouf',
                'role' => 'PMO',
                'dept' => 'Project Management Department',
                'level_name' => 'Level 4',
                'seq' => 4
            ],
            [
                'email' => 'ezzatmukhtar@amfu.net',
                'username' => 'ezzatmukhtar',
                'name' => 'ezzatmukhtar',
                'role' => 'CSO',
                'dept' => 'Security & Administration Department',
                'level_name' => 'Level 5',
                'seq' => 5
            ],
        ];

        // 3. Loop ke zariye database populate karna
        foreach ($usersData as $data) {
            
            // Department Create/Update
            $dept = Department::updateOrCreate(
                ['name' => $data['dept']]
            );

            // Approval Level Create/Update (Unique combination: dept_id + sequence)
            $level = ApprovalLevel::updateOrCreate(
                [
                    'department_id' => $dept->id, 
                    'sequence' => $data['seq']
                ],
                [
                    'name' => $data['level_name']
                ]
            );

            // User Create/Update
            // Note: Hum email aur username dono check kar rahe hain taake integrity error na aaye
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'username'          => $data['username'],
                    'name'              => $data['name'],
                    'password'          => Hash::make('12345678'),
                    'status'            => 1,
                    'approval_level_id' => $level->id, // User table mein direct foreign key
                ]
            );

            // Role assign karna (Spatie ya custom role logic)
            $user->assignRole($data['role']);

            // Department Sync (Pivot table: department_user)
            $user->departments()->sync([$dept->id]);
        }

        // 4. Extra Department (agar koi aur bhi ho)
        Department::updateOrCreate(['name' => 'Finance & Commercial Department']);
    }
}