<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Department;

class UserManagementController extends Controller
{
    // Show all users
    public function index()
{
    $users = User::with(['roles', 'department'])->get();
    return view('admin.user-management', compact('users'));
}

    // Show edit form for a user
    public function edit(User $user)
{
    $roles = Role::all();
    $departments = Department::all(); 

    return view('admin.users-edit', compact('user', 'roles', 'departments'));
}

    // Update user info + role
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name'         => 'required|string|max:255',
        'username'     => 'required|string|max:255',
        'email'        => 'required|email|unique:users,email,' . $user->id,
        'role'         => 'required|exists:roles,name',
        'department_id'=> 'nullable|exists:departments,id',
        'status'       => 'required|boolean',
    ]);

    $user->update([
        'name'          => $request->name,
        'username'      => $request->username,
        'email'         => $request->email,
        'department_id' => $request->department_id,
        'status'        => $request->status,
    ]);

    // Role Update
    $user->syncRoles([$request->role]);

    return redirect()->route('users.index')->with('success', 'User updated successfully!');
}

    //  Update status (Active / Inactive)
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $user->status = $request->status;
        $user->save();

        return redirect()->back()->with('success', 'User status updated successfully!');
    }

        public function destroy(User $user) 
    {
        $user->delete(); 

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }


    public function deletedUsers()
    {
      
        $users = User::onlyTrashed()->with('department')->get();
        return view('admin.users-deleted', compact('users')); 
    }

    
    public function restore($id)
    {
       
        $user = User::withTrashed()->findOrFail($id); 
        $user->restore();

        return redirect()->route('admin.deleted-users')->with('success', 'User restored successfully!');
    }

        public function forceDelete($id)
        {
            $user = User::withTrashed()->findOrFail($id); 
            
            $user->forceDelete(); 

            return redirect()->route('admin.deleted-users')->with('success', 'User permanently deleted!');
        }
    
}


    