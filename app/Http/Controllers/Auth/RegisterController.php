<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    use RegistersUsers {
        register as traitRegister; // original RegistersUsers method alias
    }

    protected $redirectTo = '/login'; // redirect after successful registration

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        $roles = Role::all();               // Fetch all roles
        $departments = Department::all();   // Fetch all departments

        return view('admin.register', compact('roles', 'departments'));
    }

    /**
     * Handle registration form submit
     */
    public function register(Request $request)
    {
        // Validate inputs
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'role_id'       => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Create user
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'department_id' => $request->department_id,
            'status'        => 1, // optional: active by default
        ]);

        // Assign role
        $role = Role::findById($request->role_id);
        $user->assignRole($role);

        // Flash success message
        $request->session()->flash('success', 'User registered successfully!');

        // Redirect to login page
        return redirect()->route('login');
    }
}
