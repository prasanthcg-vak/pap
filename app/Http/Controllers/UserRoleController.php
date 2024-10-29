<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    // Show the form for assigning a role to a user
    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $roles = Role::all();

        return view('user-roles.edit', compact('user', 'roles'));
    }

    // Update the role assigned to a user
    public function update(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($userId);

        // Detach any previously assigned roles and attach the new one
        $user->roles()->sync([$request->input('role_id')]);

        return redirect()->route('users.index')->with('success', 'Role updated successfully.');
    }
}
