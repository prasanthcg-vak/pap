<?php
namespace App\Http\Controllers;
use App\Models\TaskImage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SharedAssetNotification;
use App\Models\User;
use App\Models\SharedAsset;
use Illuminate\Http\Request;

class SharedAssetController extends Controller
{
    public function index()
    {
        $sharedAssets = SharedAsset::with(['task', 'asset', 'partner'])->get();
        return response()->json($sharedAssets);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'partners' => 'required|array',
            'partners.*' => 'exists:users,id',
            'start_date' => 'nullable|date',
        ]);

        if ($request->end_date != null) {
            $validated = $request->validate([
                'end_date' => 'date|after_or_equal:start_date',
            ]);
        }

        // Set default start date if not provided
        $startDate = $request->start_date ?? now()->format('Y-m-d');
        $endDate = $request->end_date ?? null;

        // Delete existing shared assets for the given asset and task
        SharedAsset::where('asset_id', $request->asset_id)
            ->where('task_id', $request->task_id)
            ->delete();

        // Insert new shared assets
        foreach ($request->partners as $partner_id) {
            SharedAsset::create([
                'partner_id' => $partner_id,
                'asset_id' => $request->asset_id,
                'task_id' => $request->task_id,
                'start_date' => $startDate,
                'end_date' => $endDate, // This will remain null if not provided
            ]);
            $partner = User::find($partner_id);

            if ($partner) {
                Mail::to($partner->email)->send(new SharedAssetNotification($partner, $request->asset_id));
            }
    
        }

        // Fetch partner email
       

        return redirect()->back()->with('success', 'Assets shared successfully!');
    }

    public function show($id)
    {
        $sharedAsset = SharedAsset::with(['task', 'asset', 'partner'])->findOrFail($id);
        return response()->json($sharedAsset);
    }

    public function update(Request $request, $id)
    {
        $sharedAsset = SharedAsset::findOrFail($id);

        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'asset_id' => 'required|exists:assets,id',
            'partner_id' => 'required|exists:partners,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $sharedAsset->update($request->all());

        return response()->json($sharedAsset);
    }

    public function destroy($id)
    {
        $sharedAsset = SharedAsset::findOrFail($id);
        $sharedAsset->delete();

        return response()->json(null, 204);
    }

    public function updateSharedAssets(Request $request)
{
    $request->validate([
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'notes' => 'nullable|string',
        'partners' => 'required|array',
        'partners.*' => 'exists:users,id',
    ]);

    $startDate = $request->start_date ?? now()->format('Y-m-d');
    $endDate = $request->end_date ?? null;

    // Delete existing shared assets for the given asset and task
    SharedAsset::where('asset_id', $request->asset_id)
        ->where('task_id', $request->task_id)
        ->delete();

    // Insert new shared assets
    foreach ($request->partners as $partner_id) {
        SharedAsset::create([
            'partner_id' => $partner_id,
            'asset_id' => $request->asset_id,
            'task_id' => $request->task_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $partner = User::find($partner_id);
        if ($partner) {
            Mail::to($partner->email)->send(new SharedAssetNotification($partner, $request->asset_id));
        }
    }

    // Store notes in TaskImage model
    TaskImage::updateOrCreate(
        ['task_id' => $request->task_id, 'id' => $request->asset_id],
        ['notes' => $request->notes]
    );

    return redirect()->back()->with('success', 'Shared asset updated successfully');
}

}
