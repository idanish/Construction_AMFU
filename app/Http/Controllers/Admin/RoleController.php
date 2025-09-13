<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

public function index()
{
    // Ab view 'admin' folder ke andar se load hoga
    $roles = Role::with('permissions')->get();
    return view('admin.roles.index', compact('roles'));
}

    // Show create form with permissions
    public function create()
    {
        // Saari permissions nikal kar view ko bhejein
        $permissions = Permission::all();
        return view('admin.roles-create', compact('permissions'));
    }

    // Store new role and assign permissions
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array', // Permissions array ko validate karein
        ]);

        // Naya role banayein
        $role = Role::create(['name' => $request->name]);
        
        // Role ko select ki hui permissions assign karein
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.create')->with('success', 'Role and permissions created successfully!');
    }
}