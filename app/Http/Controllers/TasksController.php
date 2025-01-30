<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\CampaignPartner;
use App\Models\Campaigns;
use App\Models\Category;
use App\Models\ClientPartner;
use App\Models\Image;
use App\Models\Tasks;
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
        $partners = ClientPartner::with(['client', 'partner'])
            ->where('client_id', $authId)
            ->get();
        // dd($asset);
        // $tasks = Tasks::with(['campaign', 'status'])->where('is_active', 1)->get();
        if ($role_level < 4) {
            $tasks = Tasks::with([
                'campaign.group',  // Load the group related to the campaign
                'campaign.client', // Load the client related to the campaign
                'status'           // Load the status if it's a relation
            ])->get();
        } elseif ($role_level == 4) {
            $tasks = Tasks::with(['campaign.group', 'campaign.client', 'status'])
                ->whereHas('campaign', function ($query) use ($client_id) {
                    $query->where('client_id', $client_id);
                })
                ->get();
        } elseif ($role_level == 5) {
            $tasks = Tasks::with(['campaign.group', 'campaign.client', 'status'])
                ->whereHas('campaign', function ($query) use ($client_id, $group_id) {
                    $query->where('client_id', $client_id);
                    // ->where('client_group_id', $group_id);
                })
                ->get();
        } elseif ($role_level == 6) {
            $tasks = Tasks::with(['campaign.group', 'campaign.client', 'status', 'campaign.partner'])
                ->where('partner_id', $authId)
                ->get();
        }
        // dd($tasks);

        return view('tasks.index', compact('tasks', 'campaigns', 'categories', 'assets', 'partners'));
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
        Tasks::create([
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

        return response()->json([
            'task' => $task,
            'partners' => $partners, // Return the partners list
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
        return view('tasks.show', compact('task', 'campaigns', 'categories', 'assets', 'partners', 'imageUrl'));
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

        $task = Tasks::find($id);
        // dd($task);
        $task->is_active = 0;
        $task->update();
        // $tasks->delete();
        return redirect()->route('tasks.index')->with('success', 'Task soft deleted successfully!');

    }
    public function getPartnersByCampaign($campaignId)
    {
        // Retrieve the campaign with the client and partners
        $campaign = Campaigns::with(['client','group'])->find($campaignId);

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



}
