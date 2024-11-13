<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all(); // Retrieve all roles
        $permissions = Permission::all(); // Retrieve all permissions
        $rolePermissions = []; // Empty array to handle each role's permissions separately
    
        return view('roles.index', compact('roles', 'permissions', 'rolePermissions'));
    }

    public function store(Request $request)
    {       
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $data = $request->all();
            Role::create($data);

            return response()->json(['success' => 'Role created successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the role'], 500);
        }
    }

    public function update(Request $request, Role $role)
    {
        try {
            $request->validate([
               'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $data = $request->all();
            $role->update($data);

            return response()->json(['success' => 'Role  updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the role'], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
        } catch (\ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the role'], 500);
        }
    }
}
