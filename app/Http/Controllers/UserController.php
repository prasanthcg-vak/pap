<?php

namespace App\Http\Controllers;

use App\Models\CampaignPartner;
use App\Models\ClientPartner;
use App\Models\Group;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
// use Yajra\DataTables\Facades\DataTables;
use App\Mail\UserPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'group')->get();
        // $data = User::with('roles')->get();
        // dd($users[0]->group);
        return view('users.index', compact('users'));
    }


    public function store(Request $request)
    {

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'is_active' => 'boolean',
        ]);
        if($request->role_id > 5){
            $request->validate([
                'group_id' => 'required',
            ]);
        }else{
            $request->group_id = null;
        }

        // Generate a random password for the partner
        $randomPassword = Str::random(10);
        $status = $request->has('is_active') ? $request->input('is_active') : 0;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'group_id' => (int) $request->group_id,
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
        Mail::to($request->email)->send(new UserPasswordMail($randomPassword));

        return response()->json(['success' => 'User created successfully']);

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
            if($request->role_id > 5){
                $request->validate([
                    'group_id' => 'required',
                ]);
            }else{
                $request->group_id = null;
            }

            // Update user details
            // $data = $request->all();
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['group_id'] = (int) $request->group_id;
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            // dd($data);
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

        $user = User::findOrFail($id);

        $client_partner = ClientPartner::where('partner_id', $id)->first();
        if ($client_partner != null) {
            $client_partner->delete();
            // dd($client_partner);
        }
        // $client_partner->delete();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');

    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        return response()->json(['success' => 'User activation status updated!']);
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
        // dd(Auth::user()->group_id);
        $groups = Group::get();
        return view('clientpartner.create', compact('groups'));
    }

    // Store the user and create client-partner relation
    public function store_client_partner(Request $request)
    {
        // Validate the incoming data
        // dd($request->all());
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
            'contact' => $request->partner_contact, // Hash the random password
            'pcode' => $randomPassword,
            'group_id' => (int) $request->group,  
            'is_active' => $request->status == 'active' ? 1 : 0,  // Set status
        ]);

        // Create the partner in `clientpartner` table (link the partner with the client)
        ClientPartner::create([
            'client_id' => Auth::id(), // Using the authenticated client's ID
            'partner_id' => $user->id,  // Newly created partner's ID
        ]);
        
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => 6,
            'created_at' => now(),
            'updated_at' => now()
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
        Mail::to($request->partner_email)->send(new UserPasswordMail($randomPassword));

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
        $client_partner = ClientPartner::find($id);
        if($client_partner){
            $client_partner->delete();
        }
        $user = User::find($client_partner->partner_id);
        if($user){
            $user->delete();
        }

        // dd($user);
        return redirect()->route('myprofile')->with('success', 'Partner deleted successfully!');

        
    }
    public function getPartnersByCampaign($id)
    {
        $authId = Auth::id();

        // Fetch partners related to the selected campaign
        $partners = CampaignPartner::with(['partner'])
            ->where('campaigns_id', $id)          // ->where('campaign_id', $id) // Assuming 'campaign_id' is the relation field
            ->get();

        // Return JSON response
        return response()->json($partners);
    }

    public function updatepassword(Request $request)
    {
        // Validate the input
        $request->validate([
            'newpassword' => 'required|min:8|confirmed',
            'newpassword_confirmation' => 'required',
        ]);

        // Update the password for the authenticated user
        $user = Auth::user();
        $user->password = Hash::make($request->newpassword);
        $user->save();

        // Redirect with a success message
        return redirect()->back()->with('success', 'Password updated successfully!');
    }

}
