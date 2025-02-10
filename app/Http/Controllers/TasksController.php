<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\CampaignPartner;
use App\Models\Campaigns;
use App\Models\CampaignStaff;
use App\Models\Category;
use App\Models\ClientPartner;
use App\Models\Image;
use App\Models\comment;
use App\Models\Tasks;
use App\Models\TaskStaff;
use App\Models\TaskVersion;
use App\Models\VersioningStatus;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            $campaigns = Campaigns::where('client_id', $client_id)->get();
        }
    
        $categories = Category::where('is_active', 1)->get();
        $assets = AssetType::where('is_active', 1)->get();
        $partners = ClientPartner::with(['client', 'partner'])->where('client_id', $authId)->get();
    
        $tasksQuery = Tasks::with([
            'campaign.group',
            'campaign.client',
            'status',
            'taskStaff.staff'
        ]);
    
        // Filter tasks based on role
        if ($role_level == 4) {
            $tasksQuery->whereHas('campaign', function ($query) use ($client_id) {
                $query->where('client_id', $client_id);
            });
        } elseif ($role_level == 5) {
            $tasksQuery->whereHas('campaign', function ($query) use ($client_id, $group_id) {
                $query->where('client_id', $client_id);
            })->where('marked_for_deletion', false);
        } elseif ($role_level == 6) {
            $tasksQuery->where('partner_id', $authId)->where('marked_for_deletion', false);
        }
        
    
        $tasks = $tasksQuery->get();
        $comments = comment::with('replies')->where('main_comment', 1)->get();
    
        return view('tasks.index', compact('tasks', 'campaigns', 'categories', 'assets', 'partners', 'comments'));
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

        // Validate the incoming request data
        $validatedData = $request->validate([
            'campaign_id' => 'required',
            'name' => 'required|string|max:255',
            'date_required' => 'required|date',
            // 'task_urgent' => 'sometimes|boolean',
            'size_width' => 'required|integer',
            'size_height' => 'required|integer',
            'description' => 'required|string',
            'staff' => 'required|array', // Expecting multiple staff IDs

        ]);
        $date = $validatedData['date_required'];
        $formattedDate = $date;
        // dd($formattedDate);

        // dd($request->all());
        Log::info('Incoming request for image upload', [
            'request_data' => $request->all(),
        ]);


        // Store the uploaded file in Backblaze B2
        $image = new Image();

        if ($request->hasFile('myFile')) {
            try {
                $file = $request->file('myFile');

                // Log the file details
                Log::info('File details', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);

                $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = 'images/' . $randomName;

                // Log the file path and attempt storage
                Log::info('Attempting to upload file to Backblaze', ['file_path' => $filePath]);

                try {
                    $s3Client = new S3Client([
                        'version' => 'latest',
                        'region' => 'us-east-005',
                        'endpoint' => 'https://s3.us-east-005.backblazeb2.com',
                        'credentials' => [
                            'key' => env('BACKBLAZE_KEY_ID'),
                            'secret' => env('BACKBLAZE_APPLICATION_KEY'),
                        ],
                    ]);

                    $result = $s3Client->putObject([
                        'Bucket' => 'cm-pap01',
                        'Key' => $filePath,
                        'Body' => file_get_contents($file),
                        'ACL' => 'public-read',
                    ]);

                    $extension = $file->getClientOriginalExtension();
                    $file_type = '';

                    if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                        $file_type = 'image';
                    } elseif ($extension === 'pdf') {
                        $file_type = 'document';
                    } elseif ($extension === 'mp4') {
                        $file_type = 'video';
                    } else {
                        $file_type = '';
                    }

                    // Store the image details in the database
                    $image->path = $filePath; // Assuming you have a 'path' column in your 'images' table
                    $image->file_name = $randomName; // Store the random name if needed
                    $image->file_type = $file_type;
                    $image->save();

                    // Log successful storage
                    Log::info('Image uploaded successfully', [
                        'database_image_id' => $image->id,
                    ]);

                    // Redirect back to index with success message

                } catch (Aws\Exception\AwsException $e) {

                    // Catch any AWS SDK errors
                    return response()->json(['error' => 'Backblaze upload failed: ' . $e->getAwsErrorMessage()], 500);
                } catch (Exception $e) {

                    return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
                }

            } catch (\Exception $e) {
                // Log the error
                Log::error('Image upload error', [
                    'error_message' => $e->getMessage(),
                    'request_data' => $request->all(),
                ]);
                return redirect()->back()->withErrors(['error' => 'File upload failed: ' . $e->getMessage()]);
            }
        }
        $image_id = $image->id;
        Log::info('PArtner_id', [
            'PartnerId' => $request->partner_id,
        ]);
        // Create a new task using the validated data
        $task = Tasks::create([
            'campaign_id' => (int) $request->campaign_id,
            'name' => $validatedData['name'],
            'date_required' => $formattedDate,
            'task_urgent' => $request->has('task_urgent') ? 1 : 0, // Convert checkbox value
            'size_width' => $validatedData['size_width'],
            'size_height' => $validatedData['size_height'],
            'image_id' => $image_id,
            'partner_id' => (int) $request->partner_id,
            'category_id' => (int) $request->category_id,
            'asset_id' => (int) $request->asset_id,
            'size_measurement' => $request->size_measurement,
            'description' => $validatedData['description'],
            'status_id' => null, // Set this as needed
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);
        foreach ($request->staff as $staff_id) {
            TaskStaff::create([
                'task_id' => $task->id,
                'staff_id' => $staff_id,
            ]);
        }


        // Redirect to the tasks index page with a success message
        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tasks $task)
    {
        if (request()->ajax()) {
            $partners = CampaignPartner::with([
                'partner' => function ($query) {
                    $query->select('id', 'name', 'is_active'); // Fetch only required fields
                },
            ])
                ->where('campaigns_id', $task->campaign_id)
                ->whereHas('partner', function ($query) {
                    $query->where('is_active', 1); // Filter active partners
                })
                ->get()
                ->pluck('partner'); // Extract the partner details

            $clientName = $partners->isNotEmpty() && $partners->first()->campaign && $partners->first()->campaign->client
                ? $partners->first()->campaign->client->name
                : 'No client';
            // dd($partners);

            $staffs = TaskStaff::with("staff")->where('task_id', $task->id)->get();

            return response()->json([
                'task' => $task,
                'partners' => $partners, // Return the partners list
                'staffs' => $staffs,
                'client_name' => $clientName,
                'campaigns' => Campaigns::all(),
                'categories' => Category::where('is_active', 1)->get(),
            ]);
        }

        // For non-AJAX requests, return the normal view
        $campaigns = Campaigns::all();
        $categories = Category::where('is_active', 1)->get();
        return view('tasks.edit', compact('task', 'campaigns', 'categories'));
    }

    public function list_edit(Tasks $task)
    {
        if (request()->ajax()) {
            $partners = CampaignPartner::with("partner")->where('campaigns_id', $task->campaign_id)->get();
            $campaignClient = Campaigns::with("client", "group")->where("id", $task->campaign_id)->first();
            if ($campaignClient) {
                $clientName = $campaignClient->client->name;
                $groupName = $campaignClient->group->name;
            } else {
                $clientName = "";
                $groupName = "";
            }
            $campaignStaffs = CampaignStaff::with("staff")->where("campaign_id", $task->campaign_id)->get();
            $taskStaffs = TaskStaff::with("staff")->where('task_id', $task->id)->get();

            return response()->json([
                'task' => $task,
                'partners' => $partners, // Return the partners list
                'task_staffs' => $taskStaffs,
                'campaign_staffs' => $campaignStaffs,
                'client_name' => $clientName,
                'group_name' => $groupName,
                'campaigns' => Campaigns::all(),
                'categories' => Category::where('is_active', 1)->get(),
            ]);
        }

        // For non-AJAX requests, return the normal view
        $campaigns = Campaigns::all();
        $categories = Category::where('is_active', 1)->get();
        return view('tasks.edit', compact('task', 'campaigns', 'categories'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Tasks $task)
    {
        $authId = Auth::id();
        $role_level = Auth::user()->roles->first()->role_level;
        $client_id = Auth::user()->client_id;

        if ($role_level < 4) {
            $campaigns = Campaigns::all(); // Get all campaigns for the dropdown
        } elseif ($role_level == 6) {
            $campaigns = Campaigns::with('partner')->whereHas('partner', function ($query) use ($authId) {
                $query->where('partner_id', $authId);
            })->get();
        } else {
            $campaigns = Campaigns::all()->where('client_id', $client_id);
        }
        $categories = Category::where('is_active', 1)->get();
        $assets = AssetType::where('is_active', 1)->get();
        $staffs = TaskStaff::with("staff")->where('task_id', $task->id)->get();

        $partners = CampaignPartner::with([
            'campaign.client',
            'partner.roles' => function ($query) {
                $query->where('role_level', 6); // Filter roles with role_level = 6
            }
        ])
            ->where('campaigns_id', $task->campaign_id)
            ->whereHas('partner', function ($query) {
                $query->where('is_active', 1); // Filter partners with is_active = 1
            })
            ->whereHas('partner.roles', function ($query) {
                $query->where('role_level', 6); // Ensure the partner has a role with role_level = 6
            })
            ->get();


        // Fetch the associated image for the task
        $image = $task->image_id
            ? Image::find($task->image_id)
            : null;

        $imageUrl = $image
            ? Storage::disk('backblaze')->url($image->path)
            : null;
            $versioning_status = VersioningStatus::get();

            $versioning = TaskVersion::with([
                'versionStatus',
                'asset',
                'staff',
            ])->get()->map(function ($taskVersion) {
                return [
                    'id' => $taskVersion->id,
                    'status' => $taskVersion->versionStatus ? $taskVersion->versionStatus : null,
                    'asset' => $taskVersion->asset ? [
                        'id' => $taskVersion->asset->id,
                        'file_name' => $taskVersion->asset->file_name,
                        'image' => $taskVersion->asset->path ? Storage::disk('backblaze')->url($taskVersion->asset->path) : null,
                        'thumbnail' => $taskVersion->asset->thumbnail_path
                            ? Storage::disk('backblaze')->url($taskVersion->asset->thumbnail_path)
                            : null,
                    ] : null, // Ensure asset exists
                    'staff' => $taskVersion->staff ? $taskVersion->staff : null,
                    'description' => $taskVersion->description,
                    'created_at' => $taskVersion->created_at ? $taskVersion->created_at->format('Y-m-d H:i:s') : null,
                    'updated_at' => $taskVersion->updated_at ? $taskVersion->updated_at->format('Y-m-d H:i:s') : null,
                ];
            });
            
                    // dd($versioning);
        return view('tasks.show', compact('task', 'campaigns', 'categories', 'assets', 'partners', 'imageUrl','staffs','versioning','versioning_status'));
    }


    // Update the task
    // public function update(Request $request, Tasks $task)
    // {
    //     // dd($request->all());
    //     // Validate the incoming request data
    //     $validatedData = $request->validate([
    //         'campaign_id' => 'nullable|exists:campaigns,id',
    //         'name' => 'required|string|max:255',
    //         'date_required' => 'required|date',
    //         // 'task_urgent' => 'sometimes|boolean',
    //         // 'category_id' => 'required|string|max:255',
    //         'asset' => 'required',
    //         'size_width' => 'required|integer',
    //         'size_height' => 'required|integer',
    //         'description' => 'required|string',
    //     ]);

    //     // Update the task with validated data
    //     $task->update([
    //         'campaign_id' => $validatedData['campaign_id'],
    //         'name' => $validatedData['name'],
    //         'date_required' => $validatedData['date_required'],
    //         'task_urgent' => $validatedData['task_urgent'] ?? 0,
    //         'category_id' => $request['category_id'],
    //         'asset_id' => $validatedData['asset'],
    //         'size_width' => $validatedData['size_width'],
    //         'size_height' => $validatedData['size_height'],
    //         'description' => $validatedData['description'],
    //     ]);

    //     // Redirect back with a success message
    //     return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    // }
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'campaign_id' => 'required',
            'name' => 'required|string|max:255',
            'date_required' => 'required|date',
            'size_width' => 'required|integer',
            'size_height' => 'required|integer',
            'size_measurement' => 'required|string',
            'description' => 'required|string',
            'partner_id' => 'required|integer',
            'category_id' => 'required|integer',
            'asset_id' => 'nullable|integer',
            'staff' => 'array',
        ]);

        // Retrieve the existing task
        $task = Tasks::findOrFail($id);

        // Format the date if necessary
        $formattedDate = $validatedData['date_required'];

        // Log the update request
        Log::info('Incoming request to update task', [
            'task_id' => $id,
            'request_data' => $request->all(),
        ]);

        // Handle image upload if a new file is provided
        if ($request->hasFile('myFile')) {
            try {
                $file = $request->file('myFile');
                $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = 'images/' . $randomName;

                $s3Client = new S3Client([
                    'version' => 'latest',
                    'region' => 'us-east-005',
                    'endpoint' => 'https://s3.us-east-005.backblazeb2.com',
                    'credentials' => [
                        'key' => env('BACKBLAZE_KEY_ID'),
                        'secret' => env('BACKBLAZE_APPLICATION_KEY'),
                    ],
                ]);

                $result = $s3Client->putObject([
                    'Bucket' => 'cm-pap01',
                    'Key' => $filePath,
                    'Body' => file_get_contents($file),
                    'ACL' => 'public-read',
                ]);

                $extension = $file->getClientOriginalExtension();
                $file_type = '';

                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $file_type = 'image';
                } elseif ($extension === 'pdf') {
                    $file_type = 'document';
                } elseif ($extension === 'mp4') {
                    $file_type = 'video';
                } else {
                    $file_type = '';
                }

                // Update the image in the database
                if ($task->image_id) {
                    $image = Image::findOrFail($task->image_id);
                    $image->path = $filePath;
                    $image->file_name = $randomName;
                    $image->file_type = $file_type;
                    $image->save();
                } else {
                    $image = new Image();
                    $image->path = $filePath;
                    $image->file_name = $randomName;
                    $image->file_type = $file_type;
                    $image->save();
                    $task->image_id = $image->id;
                }

                Log::info('Image updated successfully', ['image_id' => $image->id]);
            } catch (\Exception $e) {
                Log::error('Error updating image', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        // Update the task details
        $task->update([
            'campaign_id' => (int) $request->campaign_id,
            'name' => $validatedData['name'],
            'date_required' => $formattedDate,
            'task_urgent' => $request->has('task_urgent') ? 1 : 0, // Convert checkbox value
            'size_width' => $validatedData['size_width'],
            'size_height' => $validatedData['size_height'],
            'size_measurement' => $validatedData['size_measurement'],
            'partner_id' => (int) $request->partner_id,
            'category_id' => (int) $request->category_id,
            'asset_id' => (int) $request->asset_id,
            'description' => $validatedData['description'],
            'status_id' => null,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);
        TaskStaff::where('task_id', $task->id)->delete(); // Delete old assignments
        // Insert new assignments
        if ($request->has('staff')) {
            foreach ($request->staff as $staff_id) {
                TaskStaff::create([
                    'task_id' => $task->id,
                    'staff_id' => $staff_id,
                ]);
            }
        }
        // Log the successful update
        Log::info('Task updated successfully', ['task_id' => $task->id]);

        // Redirect to the tasks index page with a success message
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $task = Tasks::findOrFail($id);
        $role_level = Auth::user()->roles->first()->role_level;

        if ($role_level == 1) {
            // Super Admin can permanently delete the task
            $task->forceDelete();
            return redirect()->back()->with('success', 'Task permanently deleted.');
        } else {
            // Non-Super Admin users mark the task for deletion
            $task->update([
                'marked_for_deletion' => true,
                'deleted_by' => Auth::id(),
            ]);
            return redirect()->back()->with('success', 'Task marked for deletion.');
        }
        // return redirect()->route('tasks.index')->with('success', 'Task soft deleted successfully!');
    }


    public function getPartnersByCampaign($campaignId)
    {
        // Retrieve the campaign with the client and partners
        $campaign = Campaigns::with(['client', 'group'])->find($campaignId);

        if (!$campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }

        // Get partners related to the campaign with role_level 6
        $partners = CampaignPartner::with([
            'campaign.client',
            'partner.roles' => function ($query) {
                $query->where('role_level', 6); // Filter roles with role_level = 6
            }
        ])
            ->where('campaigns_id', $campaignId)
            ->whereHas('partner', function ($query) {
                $query->where('is_active', 1); // Filter partners with is_active = 1
            })
            ->whereHas('partner.roles', function ($query) {
                $query->where('role_level', 6); // Ensure the partner has a role with role_level = 6
            })
            ->get();

        return response()->json([
            'group' => $campaign->group,
            'client' => $campaign->client,
            'partners' => $partners,
        ]);
    }
    public function getStaffsByCampaign($campaignId)
    {
        // Retrieve the campaign with the client and partners

        $staff = CampaignStaff::with("staff")->where("campaign_id", $campaignId)->get();
        // dd($staff);
        return response()->json([
            'staffs' => $staff
        ]);
    }



}
