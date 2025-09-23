<?php

namespace App\Http\Controllers\Admin;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Show Register Form
    public function showRegisterForm()
    {
         $departments = Department::all();
        $roles = Role::all();
        return view('admin.register',compact('departments','roles')); // resources/views/admin/register.blade.php
    }

    // Handle Register Logic
    public function register(Request $request)
    {
        $request->validate([
            'username'     => 'required|string|max:255',
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username'     => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Default role Admin assign
        // $user->assignRole('Admin');

        $role = Role::findById($request->role_id);
        $user->assignRole($role);

        return redirect()->route('admin.dashboard')->with('success', 'Admin registered successfully!');
    }
}