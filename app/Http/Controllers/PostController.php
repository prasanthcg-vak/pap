<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Aws\S3\S3Client;
use Exception;

class PostController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4,mkv|max:20480', // 20MB max
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads', 'public');
        }

        $post = Post::create([
            'description' => $request->description,
            'file_path' => $filePath,
        ]);

        // Optional: Share on LinkedIn here

        return back()->with('success', 'Post created successfully.');
    }

    public function share($identifier)
    {
        try {
            // Find post by slug or GUID
            $post = Post::with('image')->where('slug', $identifier)->orWhere('guid', $identifier)->first();

            // Handle post not found
            if (!$post) {
                \Log::warning("Post not found with identifier: $identifier");
                abort(404, 'Post not found.');
            }

            // Prepare post details
            $postDetails = [
                'id' => $post->id,
                'slug' => $post->slug,
                'title' => $post->title,
                'description' => $post->description,
                'file_path' => Storage::disk('backblaze')->url($post->image->path),
                'thumbnail_path' => ($post->image->thumbnail_path) ? Storage::disk('backblaze')->url($post->image->thumbnail_path) : '',
                'file_type' => $post->image->file_type,
            ];

            \Log::info("Sharing post details", ['postDetails' => $postDetails]);

            return view('share', ['post' => $postDetails]);

        } catch (\Exception $e) {
            \Log::error("Error sharing post with identifier: $identifier", [
                'exception' => $e->getMessage(),
                'identifier' => $identifier,
            ]);
            abort(500, 'An error occurred while preparing the post for sharing.');
        }
    }   

    public function shareToTwitter($identifier)
    {
        // Find post by slug or GUID
        $post = Post::with('image')->where('slug', $identifier)->orWhere('guid', $identifier)->first();
    
        // Handle post not found
        if (!$post) {
            \Log::warning("Post not found with identifier: $identifier");
            abort(404, 'Post not found.');
        }
    
        // Prepare data for sharing
        $message = $post->description;
        $assetUrl = Storage::disk('backblaze')->url($post->image->path);
        $hashtags = 'Laravel,Backblaze';
    
        // Generate the Twitter share URL
        $twitterShareUrl = sprintf(
            'https://twitter.com/intent/tweet?text=%s&url=%s&hashtags=%s',
            urlencode($message),
            urlencode($assetUrl),
            urlencode($hashtags)
        );
    
        // Pass the Twitter share URL to the view
        return view('your-view-file', [
            'twitterShareUrl' => $twitterShareUrl,
            'post' => $post, // Pass post for additional data
        ]);
    }
    

}
