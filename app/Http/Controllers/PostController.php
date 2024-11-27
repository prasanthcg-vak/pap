<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

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

    public function share($id)
    {
        $post = Post::findOrFail($id);

        return view('share', compact('post'));
    }

}
