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
            //User & Role Management
            'create-user', 'read-user', 'update-user', 'delete-user', 'Log-deleted-user',
            'create-role', 'read-role', 'update-role', 'delete-role',
            'manage-role-permissions',

            //Departments
            'create-department', 'read-department', 'update-department', 'delete-department',

            //Request Management
            'create-request', 'read-request', 'update-request', 'delete-request', 
            'approve-request', 'reject-request', 'pending-request',

            //Budget Management
            'create-budget', 'read-budget', 'update-budget', 'delete-budget', 'approve-budget', 'reject-budget',

            //Invoice Management
            'create-invoice', 'read-invoice', 'update-invoice', 'delete-invoice', 'view-invoice',

            //Payment Management
            'create-payment', 'read-payment', 'update-payment', 'delete-payment',

            //Procurement Management
            'create-procurement', 'read-procurement', 'update-procurement', 'delete-procurement', 'approve-procurement', 'reject-procurement',

            //Report Downloads
            'request-reports', 'finance-reports', 'procurement-reports', 'audit-reports',

            //System Tools
            'backup', 'backup-restore', 'view-activity-log',

            //Personal
            'profile-settings',
            
            //Attachments
            'view attachment',

            //Approval-Level
            'read-approval', 'create-approval-level'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $superAdmin = Role::findOrCreate('Super Admin');
        $admin = Role::findOrCreate('Admin');
        $Operationrole = Role::findOrCreate('Operation Manager');
        $Procurementrole = Role::findOrCreate('Procurement Finance Manager');
        $Monitorrole = Role::findOrCreate('Monitor Manager');
        $pm = Role::findOrCreate('PM'); // (Project Manager)
        $fco = Role::findOrCreate('FCO'); // (Finance & Commercial Officer)
        $pmo = Role::findOrCreate('PMO'); // (Project Management Officer)
        $cso = Role::findOrCreate('CSO'); // (Chief Security Officer)

        $superAdmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo(Permission::all());
        $pm->givePermissionTo(['create-request', 'read-request']);
        $fco->givePermissionTo(['create-request', 'read-request']);
        $pmo->givePermissionTo(['create-request', 'read-request']);
        $cso->givePermissionTo(['create-request', 'read-request']);
        $Operationrole->givePermissionTo(['create-request', 'read-request']);
        $Procurementrole->givePermissionTo(['create-request', 'read-request']);
        $Monitorrole->givePermissionTo(['create-request', 'read-request']);
    }
}