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
}