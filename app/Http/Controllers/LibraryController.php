<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\TaskImage;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\S3\S3Client;
use Exception;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $assets = TaskImage::with('task.category', 'task.campaign.partner.partner', 'task.task_status', 'sharedAssets')
        ->whereHas('task', function ($query) {
            $query->whereColumn('image_id', 'task_images.id');
        })
        ->where('approved', 1) // Add this condition
        ->get();
    


        $task = Tasks::with('category', 'campaign.partner.partner', 'task_status', 'taskImage.sharedAssets')->get();
        // dd($task);

        // Group assets by category
        $groupedAssets = $assets->groupBy(function ($asset) {
            return $asset->task->category->category_name ?? 'Uncategorized';
        });
        // dd($groupedAssets);
        return view('library.index', compact('assets', 'groupedAssets'));
    }
    // public function index(Request $request)
    // {
    //     $assets = Tasks::with(['campaign.group', 'campaign.client', 'category', 'image'])
    //         ->get()
    //         ->map(function ($task) {
    //             return [
    //                 'id' => $task->id,
    //                 'name' => $task->name,
    //                 'description' => $task->description,
    //                 'image' => $task->image ? Storage::disk('backblaze')->url($task->image->path) : null,
    //                 'thumbnail' => $task->image && $task->image->thumbnail_path
    //                     ? Storage::disk('backblaze')->url($task->image->thumbnail_path)
    //                     : asset('/path/to/default-thumbnail.jpg'),
    //                 'image_name' => $task->image->file_name ?? null,
    //                 'image_path' => $task->image->path ?? null,
    //                 'image_type' => $task->image->file_type ?? null,
    //                 'image_id' => $task->image->id ?? null,
    //                 'campaign_name' => $task->campaign->name ?? null,
    //                 'dimensions' => $task->size_width . 'x' . $task->size_height,
    //                 'category' => $task->category->name ?? null,
    //                 'status' => $task->status_id,
    //                 'group' => $task->campaign->group,
    //                 'client' => $task->campaign->client,

    //             ];
    //         });
    //     return view('library.index', compact('assets'));
    // }
}
