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
        // Permissions aur role cache ko reset karen
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Pehle saari permissions banayen (findOrCreate har permission ko banata hai agar woh maujood na ho)
        $permissions = [
            'create-request',
            'approve-request',
            'reject-request',
            'view-reports',
            'manage-users',
            'view-departments',
            'create-departments',
            'edit-departments',
            'delete-departments',
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
            'view-budgets',
            'create-budgets',
            'edit-budgets',
            'delete-budgets',
            'view-procurements',
            'create-procurements',
            'edit-procurements',
            'delete-procurements',
            'view-service-requests',
            'create-service-requests',
            'edit-service-requests',
            'delete-service-requests',
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'manage-permissions',
            'update-profile-settings',
            'manage-site-settings',
            'manage-backup',
            'view-request-reports',
            'export-request-reports',
            'view-finance-reports',
            'export-finance-reports',
            'view-audit-reports',
            'export-audit-reports',
            'view-activity-logs',
            'view-payments',
            'create-payments',
            'edit-payments',
            'delete-payments',
            'view-requests',
            'create-requests',
            'edit-requests',
            'delete-requests',
            'view-services',
            'create-services',
            'edit-services',
            'delete-services',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // 2. Ab roles banayen
        $superAdmin = Role::findOrCreate('Super Admin');
        $admin = Role::findOrCreate('Admin');
        $pm = Role::findOrCreate('PM');

        // 3. Roles ko permissions den
        $superAdmin->givePermissionTo(Permission::all()); // Super Admin ko saari permissions den
        $admin->givePermissionTo([
            'create-request',
            'approve-request',
            'reject-request',
            'view-reports',
            'view-users',
            'create-users',
            'edit-users',
            'view-invoices',
            'create-invoices',
            'view-departments',
        ]);
        $pm->givePermissionTo([
            'create-request',
            'view-requests',
            'update-profile-settings'
        ]);
    }
}