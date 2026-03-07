<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateMaster;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function permissions_list()
    {
        $permissions = Permission::all();
        return view('admin.permissions.list', compact('permissions'));
    }

    public function permissions_create()
    {
        return view('admin.permissions.create');
    }    

    public function permissions_store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return response()->json([
            'status' => true,
            'message' => 'Permission created successfully'
        ]);
    }

    public function permissions_edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function permissions_update(Request $request)
    {
        $permission = Permission::findOrFail($request->id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id
        ]);

        $permission->update(['name' => $request->name]);

        return response()->json([
            'status' => true,
            'message' => 'Permission updated successfully'
        ]);
    }    
}
