<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Department;
use App\Models\ApprovalLevel;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // Show all users
    public function index()
{
    $users = User::with(['roles', 'department'])->get();
    return view('admin.user-management', compact('users'));
}

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        
        // Fetch all approval levels with their department to show context in the dropdown
        $approvalLevels = ApprovalLevel::with('department')
            ->orderBy('department_id')
            ->orderBy('sequence')
            ->get();

        // Assuming roles are fetched correctly
        $roles = \Spatie\Permission\Models\Role::all(); // Example fetching roles

        return view('admin.register', compact('departments', 'roles', 'approvalLevels'));
    }

    public function store(Request $request)
    {

        $request->validate([
                'name'              => 'required|string|max:255',
                'username'          => 'required|string|unique:users,username',
                // 'email'  => 'required|email|unique:users,email,' . $user->id,
                'email'             => 'required|email|unique:users,email',
                'password'          => 'required|string|min:8|confirmed',
                // 'role'              => 'required|exists:roles,name',
                'role_id'           => 'required|exists:roles,id',
                'approval_level_id' => 'nullable|exists:approval_levels,id',
                'department_id'     => 'nullable|exists:departments,id',
                // 'status'            => 'required|boolean',
            ]);

        // 1. User Create karna
        $user = \App\Models\User::create([
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            // 'password'      => $request->password,
            'password'      => Hash::make($request->password),
            'department_id' => $request->department_id,
            'approval_level_id' => $request->approval_level_id,
            // 'status'        => $request->status,
        ]);

        // 2. Role assign karna (Spatie package use karte hue)
        $role = \Spatie\Permission\Models\Role::find($request->role_id);
        if ($role) {
            $user->assignRole($role);
        }
        
       createNotification('Admin', "New user {$user->name} created by ".auth()->user()->name);
    
    return redirect()->route('admin.user-management')->with('success', 'User created successfully and role assigned.');
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
            // 'role'         => 'required|exists:roles,name',
            'role_id'       => 'required|exists:roles,id',
            'approval_level_id' => 'nullable|exists:approval_levels,id',
            'department_id'=> 'nullable|exists:departments,id',
            'status'       => 'required|boolean',
        ]);

        $user->update([
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'department_id' => $request->department_id,
            'approval_level_id' => $request->approval_level_id,
            'status'        => $request->status,
        ]);

        // Role Update
        // $user->syncRoles([$request->role]);
        $user->syncRoles([$request->role_id]);

        return redirect()->route('admin.user-management')->with('success', 'User updated successfully!');
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

        return redirect()->route('admin.user-management')->with('success', 'User deleted successfully!');
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


    