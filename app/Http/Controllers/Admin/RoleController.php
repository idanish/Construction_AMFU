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
    public function show($id)
{
    $role = Role::findOrFail($id);
    return view('admin.roles.show', compact('role'));
}

public function edit($id)
{
    $role = Role::findOrFail($id);

    // Permissions ko category wise group kar rahe hain
    $permissions = Permission::all()->groupBy(function ($permission) {
        return explode('-', $permission->name)[0]; // "user-create" → "user"
    });

    // is role ke assigned permissions ka id array
    $rolePermissions = $role->permissions->pluck('id')->toArray();

    return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:roles,name,' . $id,
    ]);

    $role = Role::findOrFail($id);
    $role->update(['name' => $request->name]);

    // update permissions
    $role->syncPermissions($request->permissions ?? []);

    return redirect()->route('roles.show')->with('success', 'Role updated successfully!');
}




}