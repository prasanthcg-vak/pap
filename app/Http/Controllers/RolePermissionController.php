<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    // Show the form to assign permissions to a role
    public function edit($roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = Permission::all();

        return view('role-permissions.edit', compact('role', 'permissions'));
    }

    // Update the permissions assigned to a role
    public function update(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('roles.index')->with('success', 'Permissions updated successfully.');
    }
}
