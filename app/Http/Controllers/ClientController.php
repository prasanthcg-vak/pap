<?php

namespace App\Http\Controllers;

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
        $clients = Client::all();
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
                // 'password' => 'required|string|min:8', // Ensure password is validated
                'role_id' => 'required|integer|exists:roles,id', // Ensure role_id exists
            ]);

            $randomPassword = Str::random(10); 

            // Wrap database operations in a transaction
            DB::beginTransaction();

            // Create the client record
            $client = Client::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'is_active' => $validatedData['is_active'] ?? 0,
            ]);


            $clientId = $client->id;

            // Create the client admin user
            $user = User::create([
                'name' => $validatedData['client_admin_name'],
                'email' => $validatedData['email'],
                // 'role_id' => $validatedData['role_id'],
                'client_id' => $clientId,
                'is_active' => $validatedData['is_active'] ?? 0,
                'password' => Hash::make($randomPassword),
            ]);

            $userId = $user->id;

            // $clientUser = ClientUser::create([
            //     'user_id' => $userId,
            //     'client_id' => $clientId,
            // ]);
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $validatedData['role_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
    

            // Commit the transaction
            DB::commit();

            Mail::to($request->email)->send(new UserPasswordMail($randomPassword));

            return response()->json(['success' => 'Client and Client Admin created successfully'], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            Log::warning('Validation error while creating client', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);

            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            // Handle all other errors
            DB::rollBack(); // Rollback the transaction if an error occurs

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


    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $client = Client::findOrFail($id);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? $request->input('is_active') : 0;

            $client->update($data);

            return response()->json(['success' => 'Client updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error updating client: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the client'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return redirect()->route('clients.index')->with('success', 'Client Group deleted successfully');
        } catch (\ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting client: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the client'], 500);
        }
    }


}

