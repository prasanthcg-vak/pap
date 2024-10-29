<?php

// app/Http/Controllers/CommentController.php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tasks;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Display all comments for a task
    public function index($taskId)
    {
        
        $task = Tasks::with([
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc'); // Order comments by creation date
            },
            'comments.replies' => function ($query) {
                $query->orderBy('created_at', 'desc'); // Order replies by creation date
            }
        ])->findOrFail($taskId);
        
        return view('comments.index', compact('task'));
        
    }

    // Store a new comment or reply
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'contents' => 'required|string',
        ]);
        
        // dd(auth()->id());

        $comment = Comment::create([
            'tasks_id' => $request->task_id,
            'parent_id' => $request->parent_id,
            'created_by' => auth()->id(), // This can be null for main comments
            'content' => $request->contents,
        ]);
        // if ($request->parent_id) {
        //     return response()->json(['reply' => $comment]);
        // }
        return response()->json([
            'id' => $comment->id,
            'parent_id' => $comment->parent_id,
            'tasks_id' => $comment->tasks_id,
            'success' => true,
            'comment' => $comment,
            'user' => auth()->user(),
            'created_at' => $comment->created_at->diffForHumans(),
            'is_reply' => $comment->parent_id !== null
        ]);
        // return response()->json(['success' => true]);
        // return back()->with('success', 'Comment added successfully!');
    }
    public function destroy($id){
        // dd(True);
        $comment = Comment::find($id);
        $comment->delete();

        return response()->json(['success' => true,'id'=>$id]);

    }
}
