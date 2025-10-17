<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request) {
    $user = User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => bcrypt('12345678'),
    ]);

    createNotification('Admin', "New user {$user->name} created by ".auth()->user()->name);
}

public function assignRole(Request $request, $id) {
    $user = User::findOrFail($id);
    $user->assignRole($request->role);

    createNotification($request->role, "You have been assigned role {$request->role} by ".auth()->user()->name, $user->id);
    createNotification('Admin', "User {$user->name} assigned role {$request->role}");
}

public function index()
{
    $users = \App\Models\User::with(['roles', 'department'])->get();
    return view('users.index', compact('users'));
}

}
