<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Tasks;
use App\Models\Campaigns;
use App\Models\AssetType;
use App\Models\CampaignPartner;
use App\Models\Category;
use App\Models\ClientPartner;
use App\Models\comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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


    public function index()
    {
        $authId = Auth::id();
        $role_level = Auth::user()->roles->first()->role_level;
        $client_id = Auth::user()->client_id;
        $group_id = Auth::user()->group_id;
    
        // Fetch campaigns based on role
        if ($role_level < 4) {
            $campaigns = Campaigns::all();
        } elseif ($role_level == 6) {
            $campaigns = Campaigns::with('partner')->whereHas('partner', function ($query) use ($authId) {
                $query->where('partner_id', $authId);
            })->get();
        } else {
            $campaigns = Campaigns::all()->where('client_id', $client_id);
        }
    
        $categories = Category::where('is_active', 1)->get();
        $assets = AssetType::where('is_active', 1)->get();
        $partners = ClientPartner::with(['client', 'partner'])
            ->where('client_id', $authId)
            ->get();
    
        // Fetch tasks based on role
        $tasksQuery = Tasks::with(['campaign.group', 'campaign.client', 'status']);
    
        if ($role_level < 4) {
            // Super Admin sees all tasks, including those marked for deletion
            $tasks = $tasksQuery->get();
        } else {
            // Non-Super Admin users only see tasks not marked for deletion
            $tasks = $tasksQuery->where('marked_for_deletion', false)->get();
        }
    
        $comments = comment::with('replies')->where('main_comment', 1)->get();
    
        return view('home.index', compact('tasks', 'campaigns', 'categories', 'assets', 'partners', 'comments'));
    }

    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexOld()
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
