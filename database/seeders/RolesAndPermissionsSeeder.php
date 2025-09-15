<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'create-request', 'approve-request', 'reject-request', 'view-reports',
            'manage-users', 'view-departments', 'create-departments', 'edit-departments',
            'delete-departments', 'view-invoices', 'create-invoices', 'edit-invoices',
            'delete-invoices', 'view-budgets', 'create-budgets', 'edit-budgets',
            'delete-budgets', 'view-procurements', 'create-procurements',
            'edit-procurements', 'delete-procurements', 'view-service-requests',
            'create-service-requests', 'edit-service-requests', 'delete-service-requests',
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
            'manage-permissions', 'update-profile-settings', 'manage-site-settings',
            'manage-backup', 'view-request-reports', 'export-request-reports',
            'view-finance-reports', 'export-finance-reports', 'view-audit-reports',
            'export-audit-reports', 'view-activity-logs', 'view-payments',
            'create-payments', 'edit-payments', 'delete-payments',
            'view-requests', 'create-requests', 'edit-requests', 'delete-requests',
            'view-services', 'create-services', 'edit-services', 'delete-services',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        
        $superAdmin = Role::findOrCreate('Super Admin');
        $admin = Role::findOrCreate('Admin');
        $pm = Role::findOrCreate('PM'); // (Project Manager)
        $fco = Role::findOrCreate('FCO'); // (Finance & Commercial Officer)
        $pmo = Role::findOrCreate('PMO'); // (Project Management Officer)
        $cso = Role::findOrCreate('CSO'); // (Chief Security Officer)

        
        $superAdmin->givePermissionTo(Permission::all()); // Super Admin ko saari permissions den
        $admin->givePermissionTo(Permission::all());
        
        $pm->givePermissionTo([
            'create-requests',
            'view-requests',
            'edit-requests',
            'view-budgets',
            'view-procurements',
            'view-services',
            'update-profile-settings'
        ]);

        $fco->givePermissionTo([
            'view-budgets',
            'edit-budgets',
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'view-payments',
            'create-payments',
            'edit-payments',
            'view-finance-reports',
            'export-finance-reports',
        ]);
        
        $pmo->givePermissionTo([
            'view-reports',
            'view-request-reports',
            'view-requests',
            'view-users',
            'view-departments',
        ]);

        $cso->givePermissionTo([
            'view-activity-logs',
            'view-audit-reports',
            'manage-backup',
            'manage-users',
            'manage-permissions',
        ]);
    }
}