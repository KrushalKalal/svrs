<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function role_list()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.list', compact('roles'));
    }

    public function role_edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function role_update(Request $request)
    {
        $request->validate([
            'permissions' => 'array'
        ]);
        $role = Role::findOrFail($request->id);
        $permissions = $request->permissions ?? [];
        $role->syncPermissions($permissions);

        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully'
        ]);
    }
}
