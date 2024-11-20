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
        // Fetch campaigns along with their related images
        $assets = Image::with('campaign') // Eager load the campaign relationship
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'image_type' => pathinfo($image->file_name, PATHINFO_EXTENSION), // Image type (extension)
                    'image' => Storage::disk('backblaze')->url($image->path) ?? null, // Assuming the image is stored on Backblaze
                    'campaign_name' => $image->campaign ? $image->campaign->name : 'No Campaign', // Access the campaign name
                    'campaign_id' => $image->campaign_id, // Access the campaign_id directly
                ];
            });
    
        // Return the view with the assets (campaign images)
        return view('images.list', compact('assets'));
    }
}
