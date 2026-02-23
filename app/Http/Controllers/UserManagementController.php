<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\Department;
use App\Models\ApprovalLevel;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index() {
        $users = User::with(['roles', 'departments', 'approvalLevel'])->get();
        return view('admin.user-management', compact('users'));
    }

    public function create() {
        $departments = Department::orderBy('name')->get();
        
        // Fetch all approval levels with their department to show context in the dropdown
        $approvalLevels = ApprovalLevel::with('department')
            ->orderBy('department_id')
            ->orderBy('sequence')
            ->get();

        $roles = Role::all();

        return view('admin.register', compact('departments', 'roles', 'approvalLevels'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'              => 'required|string|max:255',
            'username'          => 'required|string|unique:users,username',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'approval_level_id' => 'nullable|exists:approval_levels,id',
            'departments' => 'nullable|array',
            'departments.*' => 'exists:departments,id',

        ]);

        // 1. User Create
        $user = User::create([
            'name'              => $request->name,
            'username'          => $request->username,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'approval_level_id' => $request->approval_level_id,
        ]);

        // 2. Multiple Department Assign
        if ($request->has('departments')) {
            $user->departments()->sync($request->departments);
        }

        // 3. Multiple Roles Assign
        $user->syncRoles($request->roles);
        
        createNotification('Admin', "New user {$user->name} created by ".auth()->user()->name);
    
        return redirect()->route('admin.user-management')->with('success', 'User created successfully and role assigned.');
    }

    public function edit(User $user) {
        $roles = Role::all();
        $departments = Department::orderBy('name')->get();
        
        // Fetch approval levels with department context
        $approvalLevels = ApprovalLevel::with('department')
            ->orderBy('department_id')
            ->orderBy('sequence')
            ->get();

        return view('admin.users-edit', compact('user', 'roles', 'departments', 'approvalLevels'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255',
            'username'          => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'roles'             => 'required|array',
            'roles.*'           => 'exists:roles,name',
            'approval_level_id' => 'nullable|exists:approval_levels,id',
            'departments' => 'nullable|array',
            'departments.*' => 'exists:departments,id',
            'status'            => 'required|boolean',
            'password'          => 'nullable|string|min:8|confirmed',
        ]);

        // Update user data
        $userData = [
            'name'              => $request->name,
            'username'          => $request->username,
            'email'             => $request->email,
            'approval_level_id' => $request->approval_level_id,
            'status'            => $request->status,
        ];

        if ($request->has('departments')) {
            $user->departments()->sync($request->departments);
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Role Update
        $user->syncRoles($request->roles);

        return redirect()->route('admin.user-management')->with('success', 'User updated successfully!');
    }

    public function updateStatus(Request $request, User $user) {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $user->status = $request->status;
        $user->save();

        return redirect()->back()->with('success', 'User status updated successfully!');
    }

    public function destroy(User $user)  {
        $user->delete(); 

        return redirect()->route('admin.user-management')->with('success', 'User deleted successfully!');
    }

    public function deletedUsers() {
        $users = User::onlyTrashed()->with('departments')->get();
        return view('admin.users-deleted', compact('users')); 
    }

    public function restore($id) {
        $user = User::withTrashed()->findOrFail($id); 
        $user->restore();

        return redirect()->route('admin.deleted-users')->with('success', 'User restored successfully!');
    }

    public function forceDelete($id) {
        $user = User::withTrashed()->findOrFail($id); 
        $user->forceDelete(); 

        return redirect()->route('admin.deleted-users')->with('success', 'User permanently deleted!');
    }
}