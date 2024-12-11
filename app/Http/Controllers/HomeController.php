<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Tasks;
use App\Models\Campaigns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\S3\S3Client;
use Exception;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $assets = Image::with('campaign')
            ->whereNotNull('campaign_id')
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'image_type' => $image->file_type,
                    'image' => Storage::disk('backblaze')->url($image->path) ?? null, 
                    'thumbnail' => $image->thumbnail_path ? Storage::disk('backblaze')->url($image->thumbnail_path) : null, 
                    'campaign_name' => $image->campaign ? $image->campaign->name : 'No Campaign', 
                    'campaign_id' => $image->campaign_id, 
                    'campaign_status' => $image->campaign ? $image->campaign->is_active : null,
                ];
            });

        return view('images.list', compact('assets'));
    }
}
