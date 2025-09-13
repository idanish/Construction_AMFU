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
            // Users
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Roles & Permissions
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'manage-permissions', // Permission management ke liye
            
            // Departments
            'view-departments',
            'create-departments',
            'edit-departments',
            'delete-departments',
            
            // Settings & Profile
            'update-profile-settings',
            'manage-site-settings',
            'manage-backup',
            
            // Reports & Logs
            'view-request-reports',
            'export-request-reports', // Jese 'create' report
            'view-finance-reports',
            'export-finance-reports',
            'view-audit-reports',
            'export-audit-reports',
            'view-activity-logs',
            
            // Finance
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
            'view-budgets',
            'create-budgets',
            'edit-budgets',
            'delete-budgets',
            'view-payments',
            'create-payments',
            'edit-payments',
            'delete-payments',
            
            // Requests & Services
            'view-requests',
            'create-requests',
            'edit-requests',
            'delete-requests',
            'view-services',
            'create-services',
            'edit-services',
            'delete-services',
            
            // Procurements
            'view-procurements',
            'create-procurements',
            'edit-procurements',
            'delete-procurements',
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
