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
            'password' => 'required|string|min:8', // Validate password
            'role_id' => 'required|integer|exists:roles,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for logo
        ]);

        // Wrap database operations in a transaction
        DB::beginTransaction();

        // Create the client record
        $client = Client::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'is_active' => $validatedData['is_active'] ?? 0,
        ]);

        $clientId = $client->id;

        $filePath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = 'assets/logo/' . $filename;
            $file->move(public_path('assets/logo'), $filename);
        }

        // Create the client admin user
        $user = User::create([
            'name' => $validatedData['client_admin_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Use manually entered password
            'client_id' => $clientId,
            'is_active' => $validatedData['is_active'] ?? 0,
            'logo' => $filePath,
        ]);

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



    public function update(Request $request, $id)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);


            $client = Client::findOrFail($id);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? $request->input('is_active') : 0;

            $client->update($data);

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = 'assets/logo/' . $filename;

                // Move file to public directory
                $file->move(public_path('assets/logo'), $filename);

                // Update logo in client model
                $client->update(['logo' => $filePath]);
            }
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

