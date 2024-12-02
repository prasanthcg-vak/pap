<?php

namespace App\Http\Controllers;

use App\Models\Image;
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
        $assets = Tasks::with(['campaign.group','campaign.client', 'category', 'image'])
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description,
                    'thumbnail' => Storage::disk('backblaze')->url($task->image->path) ?? null,
                    'image_name' => $task->image->file_name ?? null,
                    'image_path' => $task->image->path ?? null,
                    'image_type' => $task->image->file_type ?? null,
                    'campaign_name' => $task->campaign->name ?? null,
                    'dimensions' => $task->size_width . 'x' . $task->size_height,
                    'category' => $task->category->name ?? null,
                    'status' => $task->status_id,
                    'group' => $task->campaign->group,
                    'client' => $task->campaign->client,

                ];
            });
        return view('library.index', compact('assets'));
    }
}
