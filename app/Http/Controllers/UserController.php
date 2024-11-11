<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    public function getUsersData()
    {
        $users = User::with('role')->select('id', 'name', 'email', 'username', 'contact', 'is_active', 'role_id');
        return DataTables::of($users)
            ->addColumn('role', function ($user) {
                return $user->role ? $user->role->name : 'N/A';
            })
            ->addColumn('action', function ($user) {
                return view('users.partials.actions', compact('user'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        RoleUser::create(['role_id' => $request->role_id, 'user_id' => $user->id]);
        return response()->json(['success' => 'User created successfully!']);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        RoleUser::where('user_id', $id)->update(['role_id' => $request->role_id]);
        return response()->json(['success' => 'User updated successfully!']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => 'User deleted successfully!']);
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        return response()->json(['success' => 'User activation status updated!']);
    }
}
