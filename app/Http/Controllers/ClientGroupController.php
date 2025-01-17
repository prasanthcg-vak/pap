<?php

namespace App\Http\Controllers;

use App\Models\ClientGroup;
use App\Models\Client;
use App\Models\ClientGroupPartners;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientGroupController extends Controller
{
    public function index()
    {
        
            // dd(auth()->user());
        if(auth()->user()->roles->first()->role_level >3){
            $clientGroups = ClientGroup::withCount('partners')
            ->with('client:id,name', 'partners.user')
            ->where('client_id' , auth()->user()->client_id )
            ->get();
            $clients = Client::all(['id', 'name'])->where('id',auth()->user()->client_id );
        }
        else{
            $clientGroups = ClientGroup::withCount('partners')
            ->with('client:id,name', 'partners.user')
            ->get();
            $clients = Client::all(['id', 'name']);
        }


        // dd($clientGroups);
        return view('client-groups.index', compact('clientGroups', 'clients'));
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'name' => 'required|string|max:255',
                // 'client_id' => 'required|exists:clients,id',
            ]);

            // Create the client group
            ClientGroup::create([
                'name' => $request->name,
                'client_id' => $request->client_id,
            ]);

            return response()->json(['success' => 'Client group created successfully']);
        } catch (\Throwable $e) {
            Log::error('Error creating client group: ' . $e->getMessage(), ['request' => $request->all()]);
            return response()->json(['error' => 'An error occurred while creating the client group'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'client_id' => 'required|exists:clients,id',
            ]);

            $clientGroup = ClientGroup::findOrFail($id);
            $data = $request->all();
            $clientGroup->update($data);

            return response()->json(['success' => 'Client Group updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\ModelNotFoundException $e) {
            return response()->json(['error' => 'Client Group not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error updating client group: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the client group'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $client = ClientGroup::findOrFail($id);
            $client->delete();
            return redirect()->route('client-groups.index')->with('success', 'Client Group deleted successfully');
        } catch (\ModelNotFoundException $e) {
            return response()->json(['error' => 'Client Group not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting client group: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the client group'], 500);
        }
    }
    public function partner_list($id)
    {
        $partners = ClientGroupPartners::with('user.campaignPartners')
            ->where('group_id', $id)
            ->get()
            ->map(function ($partner) {
                // Check if the user has campaignPartners
                $partner->partnerexist = $partner->user && $partner->user->campaignPartners->isNotEmpty() ? 1 : 0;
                return $partner;
            });

        // $user = User::with("campaignPartners")->where('id',7)->get();
        // dd($partners);
        return view('partners.list', compact('partners'));

    }
}
