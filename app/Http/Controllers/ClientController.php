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

    public function create()
    {
        return view('clients.create');
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

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $client->update($request->all());
        return redirect('/clients')->with('success', 'Client updated successfully!');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return redirect('/clients')->with('success', 'Client deleted successfully!');
    }
}

