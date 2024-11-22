<?php

namespace App\Http\Controllers;

use App\Models\ClientGroup;
use App\Models\User;
use Illuminate\Http\Request;

class ClientGroupController extends Controller
{
    public function index()
    {
        $clientGroups = ClientGroup::with('users')->get();
        return view('client_groups.index', compact('clientGroups'));
    }

    public function create()
    {
        return view('client_groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        ClientGroup::create($validated);
        return redirect()->route('client_groups.index')->with('success', 'Client group created successfully.');
    }

    public function addUser($id)
    {
        $clientGroup = ClientGroup::findOrFail($id);
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'Client Admin');
        })->get();

        return view('client_groups.add_user', compact('clientGroup', 'users'));
    }

    public function storeUser(Request $request, $id)
    {
        $clientGroup = ClientGroup::findOrFail($id);
        $clientGroup->users()->attach($request->user_id);
        return redirect()->route('client_groups.index')->with('success', 'User added to group successfully.');
    }
}
