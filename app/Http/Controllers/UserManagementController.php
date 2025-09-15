<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserManagementController extends Controller
{
    // Show all users
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.user-management', compact('users'));
    }

    // Show edit form for a user
    public function edit(User $user)
{
    $roles = Role::all(); // DB se sare roles fetch kar liye
    return view('admin.users-edit', compact('user', 'roles'));
}

    // Update user info + role
   public function update(Request $request, User $user)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'username'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role'  => 'required|exists:roles,name',
    ]);

    $user->update([ 
        'name'  => $request->name,
        'username'  => $request->username,
        'email' => $request->email,
    ]);

    $user->syncRoles([$request->role]);

    return redirect()->route('users.index')->with('success', 'User updated successfully!');
}

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user-management')->with('success', 'User deleted successfully!');
    }
}