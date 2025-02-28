<?php

namespace App\Http\Controllers;

use App\Mail\TaskNotification;
use App\Models\Comment;
use App\Models\Image;
use App\Models\TaskImage;
use App\Models\Tasks;
use App\Models\TaskVersion;
use App\Models\VersioningStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mail;
use Validator;

class TaskVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $staff_id = Auth::id();

        // Store the uploaded file in Backblaze B2
        $image_id = null;
        $latestVersion = TaskVersion::where('task_id', $request->task_id)
            ->max('version_number') ?? 0; // Default to 0 if no versions exis

        if ($request->hasFile('versioning-file')) {
            try {
                $file = $request->file('versioning-file'); // Single file
                $extension = $file->getClientOriginalExtension();
                $isVideoOrPdf = in_array($extension, ['mp4', 'pdf']);

                // Check if a thumbnail is required for video/PDF files
                $thumbnailPath = null;
                if ($isVideoOrPdf) {
                    if (!$request->hasFile('thumbnail')) {
                        return back()->withErrors([
                            'thumbnail' => "A thumbnail is required for video or PDF files (File: {$file->getClientOriginalName()})."
                        ])->withInput();
                    }

                    $thumbnail = $request->file('thumbnail');

                    // Validate the thumbnail file
                    $thumbnailValidation = Validator::make(
                        ['thumbnail' => $thumbnail],
                        ['thumbnail' => 'required|mimes:jpeg,png,jpg,jfif|max:10240']
                    );

                    if ($thumbnailValidation->fails()) {
                        return back()->withErrors($thumbnailValidation->errors())->withInput();
                    }
                }

                // Initialize Image model
                $image = new TaskImage();

                // Generate a random file name
                $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = 'images/' . $randomName;

                // Upload file to Backblaze
                $this->uploadToS3($file, $filePath);

                // Determine file type
                $file_type = in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' :
                    ($extension === 'pdf' ? 'document' : 'video');

                // Upload and store the thumbnail if applicable
                if ($isVideoOrPdf) {
                    $thumbnailName = 'thumb_' . Str::random(10) . '.' . $thumbnail->getClientOriginalExtension();
                    $thumbnailPath = 'images/' . $thumbnailName;
                    $this->uploadToS3($thumbnail, $thumbnailPath);
                }

                // Save file details in the database
                $image->path = $filePath;
                $image->task_id = $request->task_id;
                $image->file_name = $randomName;
                $image->file_type = $file_type;
                $image->thumbnail_path = $thumbnailPath; // Save thumbnail path
                $image->save();
                $image_id = $image->id;

                Log::info('File uploaded successfully', ['file_id' => $image->id]);

            } catch (\Exception $e) {
                Log::error('File upload error', ['error_message' => $e->getMessage()]);
                return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
            }

        }


        $taskVersion = new TaskVersion();
        $taskVersion->task_id = $request->task_id;
        $taskVersion->staff_id = $staff_id;
        $taskVersion->versioning_status_id = $request->versioning_status;
        $taskVersion->comment_id = null;
        $taskVersion->version_number = $latestVersion + 1;
        $taskVersion->description = $request->description;
        $taskVersion->asset_id = $image_id;
        $taskVersion->save();

        $comment = Comment::create([
            'tasks_id' => $request->task_id, // Ensure correct column name
            'parent_id' => null, // This is a main comment
            'main_comment' => 1, // Mark as a main comment
            'created_by' => $staff_id, // Authenticated user
            'content' => $request->description, // Use description as comment content
        ]);


        $statusName = VersioningStatus::where('id', $request->versioning_status)->value('status');
        $task = Tasks::with('campaign.client')->findOrFail($request->task_id);
        Mail::to("devtester004422@gmail.com")->send(new TaskNotification($task, $statusName));

        return redirect()->back()->with('success', 'Task Version created successfully!');
    }
    private function uploadToS3($file, $filePath)
    {
        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => 'us-east-005',
            'endpoint' => 'https://s3.us-east-005.backblazeb2.com',
            'credentials' => [
                'key' => env('BACKBLAZE_KEY_ID'),
                'secret' => env('BACKBLAZE_APPLICATION_KEY'),
            ],
        ]);

        $s3Client->putObject([
            'Bucket' => 'cm-pap01',
            'Key' => $filePath,
            'Body' => file_get_contents($file),
            'ACL' => 'public-read',
        ]);

        Log::info('File uploaded to S3', ['file_path' => $filePath]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $taskVersion = TaskVersion::with('images')->findOrFail($id);
        $image = $taskVersion->images; // Directly fetch related images

        if ($image instanceof \App\Models\TaskImage) {
            $image = [
                'id' => $image->id,
                'file_name' => $image->file_name,
                'image' => Storage::disk('backblaze')->url($image->path) ?? null,
                'thumbnail' => $image->thumbnail_path
                    ? Storage::disk('backblaze')->url($image->thumbnail_path)
                    : null,
            ];
        } else {
            $image = null; // No image found
        }
        $statusName = VersioningStatus::where('id', $taskVersion->versioning_status_id)->value('status');


        return response()->json([
            'id' => $taskVersion->id,
            'task_id' => $taskVersion->task_id,
            'version_number' => $taskVersion->version_number,
            'image' => $image,
            'versioning_status_id' => $taskVersion->versioning_status_id,
            'versioning_status_name' => $statusName,
            'description' => $taskVersion->description,
            'asset_url' => $taskVersion->asset ? asset('storage/' . $taskVersion->asset->path) : null,
            'comments' => $taskVersion->comment()->with('replies', 'user')->get()->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_by' => $comment->created_by, // User ID
                    'created_at' => $comment->created_at->diffForHumans(),
                    'created_by_name' => optional($comment->user)->name, // User Name
                    'parent_id' => $comment->parent_id,
                    'replies' => $comment->replies->map(function ($reply) {
                        return [
                            'id' => $reply->id,
                            'content' => $reply->content,
                            'created_by' => $reply->created_by,
                            'created_at' => $reply->created_at->diffForHumans(),
                            'created_by_name' => optional($reply->user)->name,
                            'parent_id' => $reply->parent_id,
                        ];
                    }),
                ];
            }),
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            $staff_id = Auth::id();
            $taskVersion = TaskVersion::findOrFail($id); // Find the task version
            // dd($request->all());
            // Update task version fields
            $taskVersion->versioning_status_id = $request->versioning_status;
            $taskVersion->description = $request->description;

            // Handle file update
            if ($request->hasFile('versioning-file')) {
                $file = $request->file('versioning-file');
                $extension = $file->getClientOriginalExtension();
                $isVideoOrPdf = in_array($extension, ['mp4', 'pdf']);

                $thumbnailPath = null;
                if ($isVideoOrPdf) {
                    if (!$request->hasFile('thumbnail')) {
                        return back()->withErrors([
                            'thumbnail' => "A thumbnail is required for video or PDF files (File: {$file->getClientOriginalName()})."
                        ])->withInput();
                    }

                    $thumbnail = $request->file('thumbnail');
                    $thumbnailValidation = Validator::make(
                        ['thumbnail' => $thumbnail],
                        ['thumbnail' => 'required|mimes:jpeg,png,jpg,jfif|max:10240']
                    );

                    if ($thumbnailValidation->fails()) {
                        return back()->withErrors($thumbnailValidation->errors())->withInput();
                    }
                }

                // Generate a random file name
                $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = 'images/' . $randomName;

                // Upload new file to Backblaze
                $this->uploadToS3($file, $filePath);

                // Determine file type
                $file_type = in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' :
                    ($extension === 'pdf' ? 'document' : 'video');

                // Upload and store the thumbnail if applicable
                if ($isVideoOrPdf) {
                    $thumbnailName = 'thumb_' . Str::random(10) . '.' . $thumbnail->getClientOriginalExtension();
                    $thumbnailPath = 'images/' . $thumbnailName;
                    $this->uploadToS3($thumbnail, $thumbnailPath);
                }

                // Update or create asset record
                if ($taskVersion->asset_id) {
                    $image = TaskImage::find($taskVersion->asset_id);
                } else {
                    $image = new TaskImage();
                    // Associate new asset
                }

                $image->path = $filePath;
                $image->task_id = $request->task_id;
                $image->file_name = $randomName;
                $image->file_type = $file_type;
                $image->thumbnail_path = $thumbnailPath;
                $image->save();
                $taskVersion->asset_id = $image->id;
            }

            // Update associated comment if exists
            if ($taskVersion->comment_id) {
                $comment = Comment::find($taskVersion->comment_id);
                if ($comment) {
                    $comment->content = $request->description; // Update comment content
                    $comment->save();
                }
            } else {
                // Create a new comment if none exists
                $comment = Comment::create([
                    'tasks_id' => $request->task_id,
                    'parent_id' => null,
                    'main_comment' => 1,
                    'created_by' => $staff_id,
                    'content' => $request->description,
                ]);

                $taskVersion->comment_id = $comment->id;
            }

            $taskVersion->save(); // Save task version updates
            $task = Tasks::find($request->task_id);

            if ($task) {
                $image = TaskImage::find($taskVersion->asset_id);
                $taskimage = TaskImage::find($task->image_id);

                // dd($taskVersion->asset_id);
                if ($image) {
                    if ($request->versioning_status == 5) {
                        $image->approved = 1;
                        $taskimage->approved = 1;
                        $image->save();
                        $taskimage->save();
                    } else {
                        $image->approved = 0;
                        $image->save();
                        $taskimage->approved = 0;
                        $taskimage->save();
                    }
                }
            }
            $statusName = VersioningStatus::where('id', $request->versioning_status)->value('status');
            $task = Tasks::with('campaign.client')->findOrFail($request->task_id);
            Mail::to("devtester004422@gmail.com")->send(new TaskNotification($task, $statusName));

            return redirect()->back()->with('success', 'Task Version updated successfully!');
        } catch (\Exception $e) {
            Log::error('Update error', ['error_message' => $e->getMessage()]);
            return response()->json(['error' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }

    public function creative_request(Request $request)
    {
        $old_task = Tasks::findOrFail($request->task_id);

        // Duplicate the task
        $newTask = $old_task->replicate();
        $newTask->created_at = now();
        $newTask->updated_at = now();
        $newTask->save();

        $newTaskId = $newTask->id; // Get new task ID

        $staff_id = Auth::id();
        $image_id = null;
        $latestVersion = TaskVersion::where('task_id', $newTaskId)
            ->max('version_number') ?? 0;

        if ($request->hasFile('versioning-file')) {
            try {
                $file = $request->file('versioning-file');
                $extension = $file->getClientOriginalExtension();
                $isVideoOrPdf = in_array($extension, ['mp4', 'pdf']);

                $thumbnailPath = null;
                if ($isVideoOrPdf) {
                    if (!$request->hasFile('thumbnail')) {
                        return back()->withErrors([
                            'thumbnail' => "A thumbnail is required for video or PDF files (File: {$file->getClientOriginalName()})."
                        ])->withInput();
                    }

                    $thumbnail = $request->file('thumbnail');
                    $thumbnailValidation = Validator::make(
                        ['thumbnail' => $thumbnail],
                        ['thumbnail' => 'required|mimes:jpeg,png,jpg|max:10240']
                    );

                    if ($thumbnailValidation->fails()) {
                        return back()->withErrors($thumbnailValidation->errors())->withInput();
                    }
                }

                $image = new TaskImage();
                $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = 'images/' . $randomName;

                $this->uploadToS3($file, $filePath);

                $file_type = in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' :
                    ($extension === 'pdf' ? 'document' : 'video');

                if ($isVideoOrPdf) {
                    $thumbnailName = 'thumb_' . Str::random(10) . '.' . $thumbnail->getClientOriginalExtension();
                    $thumbnailPath = 'images/' . $thumbnailName;
                    $this->uploadToS3($thumbnail, $thumbnailPath);
                }

                $image->path = $filePath;
                $image->task_id = $newTaskId; // Assign to new task
                $image->file_name = $randomName;
                $image->file_type = $file_type;
                $image->thumbnail_path = $thumbnailPath;
                $image->save();
                $image_id = $image->id;

                Log::info('File uploaded successfully', ['file_id' => $image->id]);

            } catch (\Exception $e) {
                Log::error('File upload error', ['error_message' => $e->getMessage()]);
                return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
            }
        }

        // Create Task Version for new task
        $taskVersion = new TaskVersion();
        $taskVersion->task_id = $newTaskId; // Use new task ID
        $taskVersion->staff_id = $staff_id;
        $taskVersion->versioning_status_id = $request->versioning_status;
        $taskVersion->comment_id = null;
        $taskVersion->version_number = $latestVersion + 1;
        $taskVersion->description = $request->description;
        $taskVersion->asset_id = $image_id;
        $taskVersion->is_creative_request = 1;
        $taskVersion->save();

        // Add a new comment for the new task
        $comment = Comment::create([
            'tasks_id' => $newTaskId, // Use new task ID
            'parent_id' => null,
            'main_comment' => 1,
            'created_by' => $staff_id,
            'content' => $request->description,
        ]);

        $statusName = VersioningStatus::where('id', $request->versioning_status)->value('status');
        $task = Tasks::with('campaign.client')->findOrFail($newTaskId);

        return redirect()->back()->with('success', 'Creative request created successfully with a new task!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
