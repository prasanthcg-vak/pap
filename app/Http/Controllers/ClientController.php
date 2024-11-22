<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Client;
use Illuminate\Http\Request;

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
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'is_active' => 'nullable|boolean',
            ]);
    
            // Create the client record
            Client::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->is_active ?? 0, // Default to inactive if not set
            ]);
    
            return response()->json(['success' => 'Client created successfully']);
        } catch (\Throwable $e) {
            Log::error('Error while creating client', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
    
            return response()->json(['error' => 'An error occurred while adding the client. Please try again.']);
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

