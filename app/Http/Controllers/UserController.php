<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function __construct()
    {
        // Middleware could be added here if needed
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $sideBar = 'dashboard';
        $title = 'dashboard';
        $users = User::all(); // Fetch all users
        $route = route('users.store');
        $method = 'POST';

        return view('users.index', compact('title', 'sideBar', 'users','route','method'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $sideBar = 'master';
        $title = 'Create User';
        $data = null;
        $route = route('users.store');
        $method = 'POST';

        return view('users.add_edit', compact('title', 'data', 'route', 'method', 'sideBar'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|integer',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'is_active' => $request->is_active ?? 1,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $id = decrypt($id); // Assuming you have a custom decrypt function
        $sideBar = 'dashboard';
        $title = 'Edit User';
        $data = User::findOrFail($id);
        $route = route('users.update', encrypt($data->id)); // Assuming you have a custom encrypt function
        $method = 'PUT';

        return view('users.add_edit', compact('title', 'data', 'route', 'method', 'sideBar'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        // $id = decrypt($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|integer',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->is_active = $request->is_active ?? 1;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        // dd($id);
        // $id = decrypt($id);
        User::destroy($id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Display the current user's profile.
     */
    public function myprofile()
    {
        $sideBar = 'myprofile';
        $title = 'My Profile';
        $user = Auth::user();

        return view('users.myprofile', compact('title', 'sideBar', 'user'));
    }

    /**
     * Update the current user's profile.
     */
    public function updateprofile(Request $request)
    {
        // dd($request->all());

        $user = Auth::user();  

        $user->update($request->only(['name', 'email', 'username','is_active']));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
