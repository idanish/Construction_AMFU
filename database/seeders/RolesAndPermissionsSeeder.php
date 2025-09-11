<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Workflow Permissions
        $permissions = [
            'create request',
            'approve request',
            'reject request',
            'view reports',
            'manage users', // Admin only
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // ✅ Roles with guard_name=web
        $pm    = Role::firstOrCreate(['name' => 'PM', 'guard_name' => 'web']);
        $fco   = Role::firstOrCreate(['name' => 'FCO', 'guard_name' => 'web']);
        $pmo   = Role::firstOrCreate(['name' => 'PMO', 'guard_name' => 'web']);
        $cso   = Role::firstOrCreate(['name' => 'CSO', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

        // ✅ Assign permissions
        $pm->syncPermissions(['create request', 'view reports']);
        $fco->syncPermissions(['approve request', 'reject request', 'view reports']);
        $pmo->syncPermissions(['approve request', 'reject request', 'view reports']);
        $cso->syncPermissions(['approve request', 'reject request', 'view reports']);
        $admin->syncPermissions(Permission::all());
    }
}
