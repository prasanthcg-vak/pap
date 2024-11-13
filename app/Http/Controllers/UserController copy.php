<?php

namespace App\Http\Controllers;

use App\Models\ClientPartner;
use DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Str;

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

        return view('users.index', compact('title', 'sideBar', 'users', 'route', 'method'));
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
        $authId = Auth::id();
        $clientPartners = ClientPartner::with(['client', 'partner'])
            ->where('client_id', $authId)
            ->get();
        // dd($clientPartners);
        $sideBar = 'myprofile';
        $title = 'My Profile';
        $user = Auth::user();

        return view('users.myprofile', compact('title', 'sideBar', 'user', 'clientPartners'));
    }

    /**
     * Update the current user's profile.
     */
    public function updateprofile(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Only allow images up to 2MB
        ]);

        $user = Auth::user();

        // Check if a new profile picture is uploaded
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Define the target path in public/assets/profile_picture
            $filePath = 'assets/profile_picture/' . $filename;

            // Store the file in the specified directory
            $file->move(public_path('assets/profile_picture'), $filename);

            // Check if there is an existing profile picture and delete it
            if (!empty($user->profile_picture) && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }

            // Update the profile picture path in the user model
            $user->profile_picture = $filePath;
        }

        // Update other fields
        $user->update($request->only(['name', 'email', 'username', 'is_active']));

        // Save the changes, including profile picture path if updated
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function create_client_partner()
    {
        return view('clientpartner.create');
    }

    // Store the user and create client-partner relation
    public function store_client_partner(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'partner_name' => 'required|string|max:255',
            'partner_email' => 'required|email|unique:users,email',  // Ensure unique email for the user
            'partner_contact' => 'required|string|max:20',
            'logo' => 'required|image|mimes:jpg,png,jpeg|max:1024',
            'status' => 'nullable|in:active,inactive',  // Validate the status if provided
        ]);

        // Generate a random password for the partner
        $randomPassword = Str::random(10);  // Generates a random string of 10 characters

        // Create the user (partner) in the `users` table
        $user = User::create([
            'name' => $request->partner_name,
            'email' => $request->partner_email,
            'password' => Hash::make($randomPassword), 
            'contact'=> $request->partner_contact, // Hash the random password
            'role_id' => 2,  // Assign a role (adjust as per your roles logic)
            'is_active' => $request->status == 'active' ? 1 : 0,  // Set status
        ]);

        // Create the partner in `clientpartner` table (link the partner with the client)
        ClientPartner::create([
            'client_id' => Auth::id(), // Using the authenticated client's ID
            'partner_id' => $user->id,  // Newly created partner's ID
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {

            $file = $request->file('logo');

            $filename = time() . '_' . $file->getClientOriginalName();

            // Define the target path in public/assets/profile_picture
            $filePath = 'assets/profile_picture/' . $filename;

            // Store the file in the specified directory
            $file->move(public_path('assets/profile_picture'), $filename);
            // Update the partner record with logo
            $user->update([
                'profile_picture' => $filePath,
            ]);
        }

        // Send the password to the partner (via email or other means)
        // You can send an email to the partner with the generated password
        // Example: Mail::to($user->email)->send(new PartnerCreated($randomPassword));

        return redirect()->route('myprofile')->with('success', 'Partner added successfully! The password has been sent to the partner.');
    }

    // Edit existing partner information
    public function edit_client_partner($id)
    {
        $clientPartner = User::findOrFail($id);
        // dd($clientPartner);
        return view('clientpartner.edit', compact('clientPartner'));
    }

    // Update the partner's details
    public function update_client_partner(Request $request, $id)
    {
        $request->validate([
            'partner_name' => 'required|string|max:255',
            'partner_email' => 'required|email|unique:users,email,' . $id,
            'partner_contact' => 'required|string|max:20',
            'status' => 'nullable|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:1024',
        ]);

        $clientPartner = ClientPartner::findOrFail($id);
        $user = User::findOrFail($id);

        // dd($user);
        // Update partner details
        $user->update([
            'name' => $request->partner_name,
            'email' => $request->partner_email,
            'contact' => $request->partner_contact,
            'is_active' => $request->status == 'active' ? 1 : 0,
        ]);

        // Handle logo upload if present
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            $filename = time() . '_' . $file->getClientOriginalName();

            // Define the target path in public/assets/profile_picture
            $filePath = 'assets/profile_picture/' . $filename;

            // Store the file in the specified directory
            $file->move(public_path('assets/profile_picture'), $filename);
            // Update the partner record with logo
            $user->update([
                'profile_picture' => $filePath,
            ]);
        }

        return redirect()->route('myprofile')->with('success', 'Partner updated successfully!');
    }


    // Delete a partner from the client-partner table
    public function destroy_client_partner($id)
    {
        // Begin transaction
        DB::beginTransaction();
        try {
            // Find the ClientPartner by ID
            $clientPartner = ClientPartner::findOrFail($id);

            // Soft delete the associated user
            $user = User::findOrFail($clientPartner->partner_id);
            $user->delete(); // Soft delete from users table

            // Soft delete the ClientPartner record
            $clientPartner->delete(); // Soft delete from clientpartner table

            // Commit transaction
            DB::commit();

            return redirect()->route('myprofile')->with('success', 'Partner and user deleted successfully!');
        } catch (\Exception $e) {
            // Rollback if there's an error
            DB::rollBack();
            return redirect()->route('myprofile')->with('error', 'An error occurred while deleting.');
        }
    }
}
