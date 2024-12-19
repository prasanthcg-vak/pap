<?php

namespace App\Http\Controllers;

use App\Models\ClientPartner;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Models\User;
use App\Models\ClientUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\UserPasswordMail;


class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with([
            'users.user.roles' => function ($query) {
                $query->select('roles.id', 'roles.name');
            }
        ])->get();

        // dd($clients[0]->users->first()->user->name);


        return view('clients.index', compact('clients'));
    }



    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'is_active' => 'nullable|boolean',
                'client_admin_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8', // Validate password
                'role_id' => 'required|integer|exists:roles,id',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for logo
            ]);

            // Wrap database operations in a transaction
            DB::beginTransaction();

            $filePath = null;
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = 'assets/logo/' . $filename;
                $file->move(public_path('assets/logo'), $filename);
            }
            // Create the client record
            $client = Client::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'logo' => $filePath,
                'is_active' => $validatedData['is_active'] ?? 0,
            ]);

            $clientId = $client->id;



            // Create the client admin user
            $user = User::create([
                'name' => $validatedData['client_admin_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // Use manually entered password
                'client_id' => $clientId,
                'is_active' => $validatedData['is_active'] ?? 0,
                'logo' => $filePath,
            ]);
            $clientuser = ClientUser::create(
                [
                    'user_id' => $user->id,
                    'client_id' => $clientId,
                ]
            );

            // Assign role to the user
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $validatedData['role_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json(['success' => 'Client and Client Admin created successfully'], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error while creating client', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error while creating client', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'error' => 'An error occurred while adding the client. Please try again later.',
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $client = Client::with(['users'])->findOrFail($id);

            // dd($client);
            // Optionally, customize the response structure
            return response()->json([
                'id' => $client->id,
                'name' => $client->name,
                'description' => $client->description,
                'is_active' => $client->is_active,
                'admin_name' => $client->users->first()?->user->name,
                'admin_email' => $client->users->first()?->user->email,
                'role_id' => $client->users->first()?->user->roles->pluck('id')->first(),
                'logo' => $client->logo,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching client data: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching client data'], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $clientuser = ClientUser::where("client_id", $id)->first();

        try {
            if ($clientuser != null) {
                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string|max:500',
                    'is_active' => 'nullable|boolean',
                    'client_admin_name' => 'required|string|max:255',
                    'password' => 'nullable|string|min:8', // Optional for updates
                    'role_id' => 'required|integer|exists:roles,id',
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for logo
                    'email' => 'required|email|unique:users,email,' . $clientuser->user_id, // Ignore the current admin email
                ]);
            } else {
                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string|max:500',
                    'is_active' => 'nullable|boolean',
                    'client_admin_name' => 'required|string|max:255',
                    'password' => 'nullable|string|min:8', // Optional for updates
                    'role_id' => 'required|integer|exists:roles,id',
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for logo
                    'email' => 'required|email|unique:users', // Ignore the current admin email
                ]);
            }



            // Begin transaction
            DB::beginTransaction();

            // Find the client record
            $client = Client::findOrFail($id);

            // Update the client logo if provided
            $filePath = $client->logo; // Retain the old logo
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = 'assets/logo/' . $filename;
                $file->move(public_path('assets/logo'), $filename);

                // Optionally, delete the old logo
                if ($client->logo && file_exists(public_path($client->logo))) {
                    unlink(public_path($client->logo));
                }
            }
            // dd($client);
            // Update the client details
            $client->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'logo' => $filePath,
                'is_active' => $validatedData['is_active'] ?? 0,
            ]);

            // Update or create the client admin user
            if ($clientuser != null) {
                $user = User::find($clientuser->user_id); // Find the user by ID
            } else {
                $user = null;
            }
            if ($user != null) {
                // Update the user's fields
                $user->update([
                    'name' => $validatedData['client_admin_name'],
                    'email' => $validatedData['email'],
                    'password' => $validatedData['password']
                        ? Hash::make($validatedData['password'])
                        : $user->password, // Retain the old password if not provided
                    'is_active' => $validatedData['is_active'] ?? 0,
                ]);
                DB::table('role_user')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'role_id' => $validatedData['role_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } else {
                $user = User::create([
                    'name' => $validatedData['client_admin_name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'is_active' => $validatedData['is_active'] ?? 0,
                    'client_id' => $client->id,
                ]);
                $clientuser = ClientUser::create(
                    [
                        'client_id' => $client->id,
                        'user_id' => $user->id,
                    ]
                );

                DB::table('role_user')->updateOrInsert(
                    ['user_id' => $user->id], // $user now contains the created model, so $user->id works
                    [
                        'role_id' => $validatedData['role_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

            }
            // Sync the role for the user


            DB::commit();

            return response()->json(['success' => 'Client and Client Admin updated successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error while updating client', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error while updating client', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'error' => 'An error occurred while updating the client. Please try again later.',
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $client = Client::with('users')->find($id); // Use find() to avoid exception if not found
            if ($client) {
                // Check if the client has users
                $clientUser = $client->users->first();
                $clientPartners = ClientPartner::where('client_id', $client->id)->get();
                if (count($clientPartners) > 0) {
                    foreach ($clientPartners as $clientPartner) {
                        $clientPartner->delete();
                    }
                }
                if ($clientUser) {
                    $clientadmin = ClientUser::find($clientUser->id); // Find ClientUser
                    if ($clientadmin) {
                        $user = User::with('roles')->find($clientadmin->user_id); // Find associated User
                        if ($user) {
                            // Delete roles associated with the user
                            DB::table('role_user')->where("user_id", $clientadmin->user_id)->delete();

                            // Delete the user
                            $user->delete();
                        }
                        // Delete the client admin
                        $clientadmin->delete();
                    }
                }
                // Delete the client
                $client->delete();
            }


            return redirect()->route('clients.index')->with('success', 'Client Group deleted successfully');
        } catch (\ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting client: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the client'], 500);
        }
    }


}

