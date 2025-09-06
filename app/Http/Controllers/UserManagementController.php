<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserManagementController extends Controller
{
    public function index()
    {
       $users = User::with('roles')->get();
        $roles = Role::all(); // sare roles bhejne
        return view('admin.user-management', compact('users', 'roles'));
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // purana role hata kar naya assign
        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'Role assigned successfully!');
    }
    
}