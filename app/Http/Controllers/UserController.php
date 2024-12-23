<?php

namespace App\Http\Controllers;

use App\Models\CampaignPartner;
use App\Models\ClientGroup;
use App\Models\ClientGroupPartners;
use App\Models\ClientPartner;
use App\Models\Group;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\ClientUser;
use Illuminate\Http\Request;
// use Yajra\DataTables\Facades\DataTables;
use App\Mail\UserPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;


class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'group', 'client')
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('role_level', [1, 4]);
            })
            ->get();
        // $data = User::with('roles')->get();
        // dd($users);
        return view('users.index', compact('users'));
    }


    public function store(Request $request)
    {
        // dd($request->all());

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Validate password
            'is_active' => 'boolean',
            'role_id' => 'required'
        ]);

        if ($request->role_id == 4 || $request->role_id == 5 || $request->role_id == 6) {
            $request->validate([
                'client_id' => 'required',
            ]);
            if ($request->role_id == 6) {
                $request->validate([
                    'group_id' => 'required',
                ]);
            }
        } else {

            $request->client_id = null;
        }

        // Generate a random password for the partner
        // $randomPassword = Str::random(10);
        $randomPassword = $request->password;
        $status = $request->has('is_active') ? $request->input('is_active') : 0;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'client_id' => (int) $request->client_id,
            'group_id' => (int) $request->group_id,
            'pcode' => $randomPassword,
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
        if ($request->role_id == 6) {

            $clientUser = ClientPartner::create([
                'partner_id' => $user->id,
                'client_id' => $request->client_id
            ]);
        }
        if ($request->role_id == 6) {

            $clientGroup = ClientGroupPartners::create([
                'user_id' => $user->id,
                'group_id' => (int) $request->group_id
            ]);
        }


        // Attempt to send email
        Mail::to($request->email)->send(new UserPasswordMail($randomPassword));

        return response()->json(['success' => 'User created successfully']);

    }

    public function update(Request $request, User $user)
    {
        try {
            // Validate input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed', // Password is optional
                'is_active' => 'boolean',
                'role_id' => 'required|exists:roles,id',
            ]);

            // Additional validation for roles
            if (in_array($request->role_id, [4, 5, 6])) {
                $request->validate(['client_id' => 'required']);
                if ($request->role_id == 6) {
                    $request->validate(['group_id' => 'required']);
                }
            } else {
                $request->merge(['client_id' => null, 'group_id' => null]); // Nullify client_id and group_id if role doesn't require them
            }

            // Prepare data for update
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'client_id' => (int) $request->client_id,
                'group_id' => (int) $request->group_id,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Update user
            $user->update($data);

            // Update roles
            if ($request->has('role_id')) {
                $user->roles()->sync([$request->input('role_id')]);
            }

            // Update ClientPartner table if role is 4, 5, or 6
            if (in_array($request->role_id, [6])) {
                ClientPartner::updateOrCreate(
                    ['partner_id' => $user->id], // Find by partner_id
                    ['client_id' => $request->client_id] // Update client_id
                );
            } else {
                // Check if the ClientPartner entry exists before attempting to delete
                if (ClientPartner::where('partner_id', $user->id)->exists()) {
                    ClientPartner::where('partner_id', $user->id)->delete();
                }
            }

            // Handle group association for role 6
            if ($request->role_id == 6) {
                ClientGroupPartners::updateOrCreate(
                    ['user_id' => $user->id],
                    ['group_id' => $request->group_id]
                );
            } else {
                // If not role 6, remove ClientGroup entry
                if (ClientGroupPartners::where('user_id', $user->id)->exists()) {
                    ClientGroupPartners::where('user_id', $user->id)->delete();
                }
            }

            return response()->json(['success' => 'User updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error while updating user', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return response()->json([
                'error' => 'An error occurred while updating the user. Please try again later.',
            ], 500);
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
        $authId = Auth::user()->client_id;
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
            'contact' => 'required|digits:10', // Ensure it is exactly 10 digits
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
        $user->update($request->only(['name', 'email', 'username', 'is_active', 'contact']));

        // Save the changes, including profile picture path if updated
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function create_client_partner()
    {
        // dd(Auth::user()->group_id);
        $client_id = Auth::user()->client_id;

        $groups = ClientGroup::where("client_id", $client_id)->get();
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
        $client_id = Auth::user()->client_id;

        // Create the user (partner) in the `users` table
        $user = User::create([
            'name' => $request->partner_name,
            'email' => $request->partner_email,
            'password' => Hash::make($randomPassword),
            'contact' => $request->partner_contact, // Hash the random password
            'pcode' => $randomPassword,
            'client_id' => $client_id,
            'group_id' => (int) $request->group,
            'is_active' => $request->status == 'active' ? 1 : 0,  // Set status
        ]);

        // Create the partner in `clientpartner` table (link the partner with the client)
        ClientPartner::create([
            'client_id' => Auth::user()->client_id, // Using the authenticated client's ID
            'partner_id' => $user->id,  // Newly created partner's ID
        ]);

        ClientGroupPartners::create([
            'user_id' => $user->id,
            'group_id' => $request->group,
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
        $client_id = Auth::user()->client_id;
        $group_id = Auth::user()->group_id;
        $clientPartner = User::findOrFail($id);
        $groups = ClientGroup::where("client_id", $client_id)->get();

        $previousUrl = URL::previous();
        $returnUrl = 'myprofile';
        $previousPageGroupId = 0;
        // dd($previousUrl);
        if (str_contains(parse_url($previousUrl, PHP_URL_PATH), 'partnerlist')) {
            $returnUrl = 'partnerlist';
            $segments = explode('/', parse_url($previousUrl, PHP_URL_PATH));
            $previousPageGroupId = end($segments);
            $groups = ClientGroup::get();

        }
        return view('clientpartner.edit', compact('clientPartner', 'groups', 'group_id', 'returnUrl', 'previousPageGroupId'));
    }

    // Update the partner's details
    public function update_client_partner(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'partner_name' => 'required|string|max:255',
            'partner_email' => 'required|email|unique:users,email,' . $id,
            'partner_contact' => 'required|string|max:20',
            'status' => 'nullable|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:1024',
        ]);

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

        if ($request->returnURL == "myprofile") {
            return redirect()->route('myprofile')->with('success', 'Partner updated successfully!');
        } else {
            return redirect()->route('partnerlist', ['id' => $request->previousPageGroupId])->with('success', 'Partner updated successfully!');

        }

    }
    public function test1(Request $request, $id)
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

        dd("test");
    }

    // Delete a partner from the client-partner table
    public function destroy_client_partner($id)
    {
        // Begin transaction
        $client_partner = ClientPartner::find($id);
        if ($client_partner) {
            $client_partner->delete();
        }
        $user = User::find($client_partner->partner_id);
        if ($user) {
            $user->delete();
        }

        // dd($user);
        return redirect()->route('myprofile')->with('success', 'Partner deleted successfully!');


    }
    public function getPartnersByCampaign($id)
    {
        $authId = Auth::id();

        // Fetch partners related to the selected campaign
        $partners = CampaignPartner::with([
            'partner' => function ($query) {
                $query->where('is_active', 1); // Only get active partners
            }
        ])
            ->where('campaigns_id', $id)
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
        $user->pcode = $request->newpassword;
        $user->save();

        // Redirect with a success message
        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function resendEmail($id)
    {
        // Fetch the user by ID
        $user = User::findOrFail($id);

        // Assuming the password was stored or needs regeneration
        // Option 1: Regenerate Password (if not stored securely)
        if ($user->pcode != null) {
            // dd();
            $randomPassword = $user->pcode;
        } else {
            $randomPassword = Str::random(10);

            // Update the password in the database (if required)
            $user->update([
                'password' => Hash::make($randomPassword),
            ]);
        }



        // Option 2: Fetch stored password (if available)
        // This is not recommended for security reasons
        // $randomPassword = "Fetch from storage logic here";

        // Send the email
        try {
            Mail::to($user->email)->send(new UserPasswordMail($randomPassword));
            return response()->json(['success' => 'Email resent successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to resend email'], 500);
        }
    }
    public function unblock($id)
    {
        $user = User::findOrFail($id);

        // Unblock the user and reset attempts
        $user->update([
            'is_blocked' => 0,
            'login_attempts' => 0,
        ]);

        return back()->with('success', 'User has been unblocked successfully.');
    }



}
