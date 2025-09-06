<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call roles and permissions seeder
        $this->call(RolesAndPermissionsSeeder::class);

        // ✅ Create default admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'], // unique check
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'), // default password
            ]
        );

        // Assign admin role
        $admin->assignRole('Admin');
    }
}
