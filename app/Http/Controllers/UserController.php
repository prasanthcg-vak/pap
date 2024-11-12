<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
// use Yajra\DataTables\Facades\DataTables;
use App\Mail\UserPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }


    public function store(Request $request)
    {
        try {
            // Validate the incoming data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email', 
                'is_active' => 'boolean',
            ]);

            // Generate a random password for the partner
            $randomPassword = Str::random(10);
            $status = $request->has('is_active') ? $request->input('is_active') : 0;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($randomPassword), 
                'is_active' => $status, 
            ]);

            // Assign Role
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $request->role_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

 
            // Attempt to send email
            Mail::to('k7.cgvak@gmail.com')->send(new UserPasswordMail($randomPassword));

            return response()->json(['success' => 'User created successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating user or sending email: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the user'], 500);
        }
    }


    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $request->user_id,  
                'is_active' => 'boolean', 
                'role_id' => 'required|exists:roles,id' 
            ]);
    
            // Update user details
            // $data = $request->all();
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['is_active'] = $request->has('is_active') ? 1 : 0; 
            $user->update($data);

            if ($request->has('role_id')) {
                $user->roles()->sync([$request->input('role_id')]); 
            }

            return response()->json(['success' => 'User updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the user'], 500);
        }
    }
    

    public function destroy($id)
    {
        try {
            $assetType = User::findOrFail($id);
            $assetType->delete();

            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } catch (\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the users'], 500);
        }
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        return response()->json(['success' => 'User activation status updated!']);
    }
}
