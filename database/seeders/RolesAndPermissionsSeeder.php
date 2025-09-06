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
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Roles for workflow
        $pm   = Role::firstOrCreate(['name' => 'PM']);
        $fco  = Role::firstOrCreate(['name' => 'FCO']);
        $pmo  = Role::firstOrCreate(['name' => 'PMO']);
        $cso  = Role::firstOrCreate(['name' => 'CSO']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);

        // Assign permissions
        $pm->syncPermissions(['create request', 'view reports']);
        $fco->syncPermissions(['approve request', 'reject request', 'view reports']);
        $pmo->syncPermissions(['approve request', 'reject request', 'view reports']);
        $cso->syncPermissions(['approve request', 'reject request', 'view reports']);
        $admin->syncPermissions(Permission::all());
    }
}
