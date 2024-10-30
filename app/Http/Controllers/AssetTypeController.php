<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use Illuminate\Http\Request;

class AssetTypeController extends Controller
{
    public function index()
    {
        $assetTypes = AssetType::all();
        return view('asset_types.index', compact('assetTypes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type_name' => 'required|string|max:255',
                'type_description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? $request->input('is_active') : 0;

            AssetType::create($data);

            return response()->json(['success' => 'Asset type created successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the asset type'], 500);
        }
    }

    public function update(Request $request, AssetType $assetType)
    {
        try {
            $request->validate([
                'type_name' => 'required|string|max:255',
                'type_description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? $request->input('is_active') : 0;

            $assetType->update($data);

            return response()->json(['success' => 'Asset type updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the asset type'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $assetType = AssetType::findOrFail($id);
            $assetType->delete();

            return redirect()->route('asset-types.index')->with('success', 'Asset type deleted successfully');
        } catch (\ModelNotFoundException $e) {
            return response()->json(['error' => 'Asset type not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the asset type'], 500);
        }
    }
}
