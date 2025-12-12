<?php

// Dummy code maybe not working

// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use App\Models\Department;
// use App\Models\ApprovalLevel;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;

// class UserController extends Controller
// {
//     public function store(Request $request) {

//         $request->validate([
//             'username' => 'required|string|max:255|unique:users,username',
//             'name'     => 'required|string|max:255',
//             'email'    => 'required|email|max:255|unique:users,email',
//             'password' => 'required|string|min:8|confirmed', // 'confirmed' check karega ki password_confirmation match ho
            
//             // Blade se aane waale fields
//             'department_id'     => 'required|exists:departments,id',
//             'role_id'           => 'required|exists:roles,id',
            
//             // Approval system ke liye naya field
//             'approval_level_id' => 'nullable|exists:approval_levels,id', 
//         ]);

//         $user = User::create([
//             'name'     => $request->name,
//             'username' => $request->username,
//             'email'    => $request->email,
//             'password' => Hash::make($request->password), // Sahi password hash karein
            
//             // New fields jo Blade se aayenge
//             'department_id'     => $request->department_id,
//             'approval_level_id' => $request->approval_level_id, // Nullable hai
//         ]);


//         // 3. **Role Assign Karna (Spatie Package)**
//         $role = \Spatie\Permission\Models\Role::find($request->role_id);
//         if ($role) {
//             $user->assignRole($role);
//         }

//     createNotification('Admin', "New user {$user->name} created by ".auth()->user()->name);
    
//     return redirect()->route('users.index')->with('success', 'User created successfully and role assigned.');
    
// }

// public function assignRole(Request $request, $id) {
//     $user = User::findOrFail($id);
//     $user->assignRole($request->role);

//     createNotification($request->role, "You have been assigned role {$request->role} by ".auth()->user()->name, $user->id);
//     createNotification('Admin', "User {$user->name} assigned role {$request->role}");
// }

// public function index()
// {
//     $users = \App\Models\User::with(['roles', 'department'])->get();
//     return view('users.index', compact('users'));
// }

// }