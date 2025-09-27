<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();

        return view('permissions.index', compact('users', 'roles', 'permissions'));
    }

    /**
     * Users ki permissions ka page dikhane ke liye
     */
    public function editPermissions(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('permissions.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Permissions ko update karne ke liye
     */
    public function updatePermissions(Request $request, User $user)
{
    $user->syncRoles($request->input('roles', []));
    $user->syncPermissions($request->input('permissions', []));

    // Yahan redirect change karein
    return redirect()->route('users.index')->with('success', 'Permissions updated successfully.');
}


}