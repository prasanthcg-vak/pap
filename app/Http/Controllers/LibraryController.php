<?php

namespace App\Http\Controllers;

use App\Models\Campaigns;
use App\Models\Category;
use App\Models\Image;
use App\Models\Post;
use App\Models\SharedAsset;
use App\Models\TaskImage;
use App\Models\Tasks;
use App\Models\VersioningStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\S3\S3Client;
use Exception;

class LibraryController extends Controller
{
    public function index(Request $request)
    {

        $authId = Auth::id();

        $assetsQuery = TaskImage::with('task.category', 'task.campaign.partner.partner', 'task.task_status', 'sharedAssets')
            ->whereHas('task', function ($query) {
                $query->whereColumn('image_id', 'task_images.id');
            })
            ->where('approved', 1); // Add this condition

        // Apply the `whereHas` condition only if `auth_user_role_level() == 6`
        if (auth_user_role_level() == 6) {
            $assetsQuery->whereHas('sharedAssets', function ($query) use ($authId) {
                $query->where('partner_id', $authId);
            });
        }

        // Get the final result
        $assets = $assetsQuery->get();

        $categories = Category::all()->pluck('category_name')->toArray();

        $task = Tasks::with('category', 'campaign.partner.partner', 'task_status', 'taskImage.sharedAssets')->get();

        // Group assets by category
        $groupedAssets = $assets->groupBy(function ($asset) {
            return $asset->task->category->category_name ?? 'Uncategorized';
        });

        // Ensure all categories exist in the grouped assets list
        foreach ($categories as $category) {
            if (!isset($groupedAssets[$category])) {
                $groupedAssets[$category] = collect(); // Empty collection for missing categories
            }
        }

        // Fetch the task description and generate post links
        foreach ($assets as $asset) {
            $description = '';
            $title = '';
            $postUrl = '';
            $socialLinks = [];

            if ($asset->task->post_type === 'task') {
                $task = Tasks::find($asset->task_id);
                if ($task) {
                    $description = $task->description;
                    $title = $task->name;
                }
            } else {
                $campaign = Campaigns::find($asset->task->campaign_id);
                if ($campaign) {
                    $description = $campaign->description;
                    $title = $campaign->name;
                }
            }
            // dd($campaign);

            // Create the post
            // Check if a post already exists for this image_id
            $post = Post::where('image_id', $asset->id)->first();

            if (!$post) {
                // Create the post only if it doesn't exist
                $post = Post::create([
                    'title' => $title ?? '',
                    'description' => $description,
                    'image_id' => $asset->id,
                ]);
            }


            $postUrl = route('posts.share', $post->slug);

            // Prepare social media links
            $socialLinks = [
                'linkedin' => "https://www.linkedin.com/shareArticle?mini=true&url=" . urlencode($postUrl) . "&summary=" . urlencode($description),
                'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($postUrl),
                'twitter' => "https://twitter.com/intent/tweet?url=" . urlencode($postUrl) . "&text=" . urlencode($description),
                'reddit' => "https://www.reddit.com/submit?url=" . urlencode($postUrl) . "&title=" . urlencode($description),
            ];

            // Attach social links to asset object (if needed)
            $asset->post_url = $postUrl;
            $asset->social_links = $socialLinks;
        }

        // Now, you can pass `$groupedAssets` and `$assets` to the view.

        $versioning_status = VersioningStatus::get();
        // dd($groupedAssets);
        if (auth_user_role_level() == 6) {
            return view('library.partner-view', compact('assets', 'groupedAssets', 'versioning_status'));
        } else {
            return view('library.index', compact('assets', 'groupedAssets'));
        }
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
