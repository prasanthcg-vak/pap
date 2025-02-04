<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Campaigns;
use App\Models\CampaignStaff;
use App\Models\Category;
use App\Models\Client;
use App\Models\ClientGroup;
use App\Models\ClientGroupPartners;
use App\Models\ClientPartner;
use App\Models\Status;
use App\Models\Tasks;
use App\Models\Post;
use App\Models\Image;
use App\Models\User;
use App\Models\UserPermissions;
use App\Models\CampaignPartner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Aws\S3\S3Client;
use Exception;

class CampaignsController extends Controller
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

        $groups = [];
        $groups = ClientGroup::where("client_id", $client_id)->get();
        $query = Campaigns::with('images', 'client', 'group');

        if ($role_level < 4) {
            $query->with('tasks');
        } elseif ($role_level == 4 || $role_level == 5) {
            $query->where('client_id', $client_id);
        }

        if ($role_level == 6) {
            $query->with('partner')
                ->whereHas('partner', function ($q) use ($authId) {
                    $q->where('partner_id', $authId);
                });
        }

        if ($role_level > 1) {
            $query->where('status_id', '!=', 5); // Exclude status_id = 5 for role levels > 1
        }

        $campaigns = $query->orderBy('id', 'asc')->with("status")->get();

        $partners = ClientPartner::with([
            'client',
            'partner' => function ($query) {
                $query->where('is_active', 1); // Add condition to get only active partners
            }
        ])
            ->where('client_id', $authId)
            ->get();
        $staffs = User::with("roles")
            ->whereHas("roles", function ($query) {
                $query->where("role_level", 3);
            })
            ->get();
        // dd($campaigns);

        $clients = Client::get();
        $sideBar = 'dashboard';
        $title = 'dashboard';
        return view('campaigns.index', compact('campaigns', 'partners', 'clients', 'role_level', 'groups', 'client_id', 'staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (hasPermission('campaigns_create') == false) {
            return redirect('accessdenied');
        }
        $sideBar = 'master';
        $title = 'Create Campaign';
        $data = "";
        $status = Status::where('is_active', 1)->get();
        $route = route('campaigns.store');
        $method = 'POST';
        return view('campaigns.add_edit', compact('title', 'data', 'route', 'method', 'sideBar', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'due_date' => 'required',
            'additional_images.*' => 'nullable|mimes:jpeg,png,jpg,mp4,pdf,jfif|max:51200', // 50 MB limit
            'additional_images' => 'nullable|array',
            'thumbnail' => 'nullable|array',
            'thumbnail.*' => 'file|mimes:jpeg,png,jpg,jfif|max:10240', // 10 MB limit for thumbnails
        ]);

        if ($request->hasFile('additional_images')) {
            $thumbnails = $request->file('thumbnail') ?? [];
            $thumbnailIndex = 0; // Keep track of thumbnail index

            foreach ($request->file('additional_images') as $key => $file) {
                $extension = $file->getClientOriginalExtension();
                $isVideoOrPdf = in_array($extension, ['mp4', 'pdf']);

                if ($isVideoOrPdf) {
                    // Check if a thumbnail exists for this video/PDF file
                    $thumbnail = $thumbnails[$thumbnailIndex] ?? null;

                    if (!$thumbnail) {
                        return back()->withErrors([
                            'thumbnail' => "Thumbnails are required for video or PDF files (File: {$file->getClientOriginalName()})."
                        ])->withInput();
                    }

                    // Validate the thumbnail file
                    $thumbnailValidation = Validator::make(
                        ['thumbnail' => $thumbnail],
                        ['thumbnail' => 'required|mimes:jpeg,png,jpg,jfif|max:10240']
                    );

                    if ($thumbnailValidation->fails()) {
                        return back()->withErrors($thumbnailValidation->errors())->withInput();
                    }

                    // Increment thumbnail index only after successful validation
                    $thumbnailIndex++;
                }
            }
        }

        // Log incoming request
        Log::info('Incoming campaign request', ['request_data' => $request->all()]);
        // dd($request->staff);
        // Create Campaign entry
        $data = new Campaigns();
        $data->name = $request->name;
        $data->description = $request->description;
        $data->due_date = $request->due_date;
        $data->status_id = $request->status_id;
        $data->client_id = (int) $request->client;
        $data->client_group_id = (int) $request->clientGroup;
        $data->is_active = 1;
        $data->save();

        // Upload additional images and thumbnails
        if ($request->hasFile('additional_images')) {
            $thumbnails = $request->file('thumbnail') ?? [];
            $thumbnailIndex = 0; // Keep track of thumbnail index

            foreach ($request->file('additional_images') as $key => $file) {
                try {
                    $extension = $file->getClientOriginalExtension();
                    $isVideoOrPdf = in_array($extension, ['mp4', 'pdf']);

                    // Check if a thumbnail exists for this video/PDF file
                    $thumbnail = $thumbnails[$thumbnailIndex] ?? null;

                    $image = new Image();

                    // Upload main file
                    $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $filePath = 'images/' . $randomName;

                    // Upload file to Backblaze
                    $this->uploadToS3($file, $filePath);

                    // Determine file type
                    $extension = $file->getClientOriginalExtension();
                    $file_type = in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' :
                        ($extension === 'pdf' ? 'document' : 'video');

                    // Upload thumbnail if provided
                    $thumbnailPath = null;
                    if ($isVideoOrPdf) {
                        if ($thumbnail) {
                            $thumbnailName = 'thumb_' . Str::random(10) . '.' . $thumbnail->getClientOriginalExtension();
                            $thumbnailPath = 'images/' . $thumbnailName;
                            $this->uploadToS3($thumbnail, $thumbnailPath);
                        }
                    }

                    // Save file details in the database
                    $image->path = $filePath;
                    $image->campaign_id = $data->id;
                    $image->file_name = $randomName;
                    $image->file_type = $file_type;
                    $image->thumbnail_path = $thumbnailPath; // Save thumbnail path
                    $image->save();

                    Log::info('File uploaded successfully', ['file_id' => $image->id]);
                } catch (\Exception $e) {
                    Log::error('File upload error', ['error_message' => $e->getMessage()]);
                    return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
                }
            }
        }

        // Handle related partners
        if (isset($request->related_partner)) {
            foreach ($request->related_partner as $partner) {
                $campaignPartner = new CampaignPartner();
                $campaignPartner->campaigns_id = $data->id;
                $campaignPartner->partner_id = (int) $partner;
                $campaignPartner->save();
            }
        }
        if (isset($request->staff)) {
            foreach ($request->staff as $staff) {
                $campaignStaff = new CampaignStaff();
                $campaignStaff->campaign_id = $data->id;
                $campaignStaff->staff_id = (int) $staff;
                $campaignStaff->save();
            }
        }

        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
    }

    /**
     * Upload file to S3.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $filePath
     * @return void
     * @throws \Exception
     */
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

    public function storeOld(Request $request)
    {

        // dd($request->all());

        $request->validate(
            [
                'name' => 'required',
                'description' => 'required',
                'due_date' => 'required',
                // 'status_id' => 'required',                
                'additional_images.*' => 'nullable|mimes:jpeg,png,jpg,mp4,pdf,jfif|max:51200',
                'additional_images' => 'nullable|mimes:jpeg,png,jpg,mp4,pdf,jfif|max:51200', // 50 MB limit
            ]
        );
        // dd($request->all());
        Log::info('Incoming request for image upload here', [
            'request_data' => $request->all(),
        ]);
        $data = new Campaigns();
        $data->name = $request->name;
        $data->description = $request->description;
        $data->due_date = $request->due_date;
        $data->status_id = null;
        $data->client_id = (int) $request->client;
        $data->client_group_id = (int) $request->clientGroup;
        // $data->image_id = $image->id;
        $data->is_active = 1;
        $data->save();
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $file) {
                try {
                    $image = new Image();

                    // Generate a random name for the file
                    $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $filePath = 'images/' . $randomName;

                    // Log file details
                    Log::info('Processing file', [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'file_path' => $filePath,
                    ]);

                    // Backblaze S3 client configuration
                    $s3Client = new S3Client([
                        'version' => 'latest',
                        'region' => 'us-east-005',
                        'endpoint' => 'https://s3.us-east-005.backblazeb2.com',
                        'credentials' => [
                            'key' => env('BACKBLAZE_KEY_ID'),
                            'secret' => env('BACKBLAZE_APPLICATION_KEY'),
                        ],
                    ]);

                    // Upload the file to Backblaze
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
                    // Save file details in the database
                    $image->path = $filePath;
                    $image->campaign_id = $data->id;
                    $image->file_name = $randomName;
                    $image->file_type = $file_type;
                    $image->save();


                    Log::info('File uploaded successfully', [
                        'file_id' => $image->id,
                    ]);

                } catch (\Aws\Exception\AwsException $e) {
                    Log::error('AWS S3 Upload Error', ['error_message' => $e->getAwsErrorMessage()]);
                    return response()->json(['error' => 'Backblaze upload failed: ' . $e->getAwsErrorMessage()], 500);
                } catch (\Exception $e) {
                    Log::error('File upload error', ['error_message' => $e->getMessage()]);
                    return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
                }
            }

            // If all files uploaded successfully
            // return response()->json(['success' => 'Files uploaded successfully']);
        }

        // dd($image->id);
        // dd("test");



        $id = $data->id;

        if (isset($request->related_partner)) {
            foreach ($request->related_partner as $partner) {
                $data = new CampaignPartner();
                $data->campaigns_id = $id;
                $data->partner_id = (int) $partner;
                $data->save();
            }
        }

        return redirect()->route('campaigns.index')->with('success', 'campaigns Created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $campaign = Campaigns::with('group')->findOrFail($id);
        $tasks = Tasks::with('status')->where('campaign_id', $id)->get();
        $role_level = Auth::user()->roles->first()->role_level;

        $images = Image::where('campaign_id', $id)->get(['id', 'file_name', 'path', 'file_type', 'thumbnail_path']);
        $categories = Category::where('is_active', 1)->get();
        $assets = AssetType::where('is_active', 1)->get();
        $campaignStaffs = CampaignStaff::with("staff")->where("campaign_id",$id)->get();
        // Retrieve the URLs for each image
        $imageUrls = $images->map(function ($image) {
            return [
                'image_id' => $image->id,
                'name' => $image->file_name,
                'path' => $image->path,
                'image_type' => $image->file_type,
                'url' => Storage::disk('backblaze')->url($image->path),
                'thumbnail' => ($image->thumbnail_path) ? Storage::disk('backblaze')->url($image->thumbnail_path) : '',
            ];
        });
        $partners = ClientPartner::all(); // Assuming you have a Partner model
        return view('campaigns.show', compact('campaign', 'partners', 'assets', 'categories', 'tasks', 'imageUrls', 'role_level','campaignStaffs'));
    }

    public function showTasks($id)
    {
        $campaign = Campaigns::findOrFail($id);
        $tasks = Tasks::with('status')->where('campaign_id', $id)->get();

        $images = Image::where('campaign_id', $id)->get(['id', 'file_name', 'path', 'file_type', 'thumbnail_path']);

        // Retrieve the URLs for each image
        $imageUrls = $images->map(function ($image) {
            return [
                'image_id' => $image->id,
                'name' => $image->file_name,
                'path' => $image->path,
                'image_type' => $image->file_type,
                'url' => Storage::disk('backblaze')->url($image->path),
                'thumbnail' => ($image->thumbnail_path) ? Storage::disk('backblaze')->url($image->thumbnail_path) : '',
            ];
        });
        $partners = ClientPartner::all(); // Assuming you have a Partner model
        return view('campaigns.showTask', compact('campaign', 'partners', 'tasks', 'imageUrls'));
    }

    public function assetsList($id)
    {
        $campaign = Campaigns::findOrFail($id);
        $assets = Image::with('campaign')
            // ->whereNotNull('campaign_id')
            ->where('campaign_id', $id)
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    // 'image_type' => pathinfo($image->file_name, PATHINFO_EXTENSION), 
                    'image_type' => $image->file_type,
                    'image' => Storage::disk('backblaze')->url($image->path) ?? null,
                    'campaign_name' => $image->campaign ? $image->campaign->name : 'No Campaign',
                    'campaign_id' => $image->campaign_id,
                    'campaign_status' => $image->campaign ? $image->campaign->is_active : null,
                ];
            });

        return view('campaigns.list', compact('assets', 'campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($encryptedId)
    {
        try {
            // Decrypt the incoming campaign ID
            $campaignId = Crypt::decryptString($encryptedId);

            // Fetch the campaign details with necessary relationships
            $campaign = Campaigns::with(['images', 'client', 'group', 'tasks', 'partner', 'staff'])
                ->findOrFail($campaignId);

            // Map the images to include the full URL for both image and thumbnail
            $images = $campaign->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'image' => Storage::disk('backblaze')->url($image->path) ?? null,
                    'thumbnail' => $image->thumbnail_path
                        ? Storage::disk('backblaze')->url($image->thumbnail_path)
                        : null,
                ];
            });

            // Get groups belonging to the client of this campaign
            $clientGroups = ClientGroup::where('client_id', $campaign->client_id)->get();

            // Get partners for the campaign group
            $groupPartners = ClientGroupPartners::with('user')->where('group_id', $campaign->Client_group_id)->get();

            $campaign_staff = CampaignStaff::with("staff")->where("campaign_id", $campaignId)->get();
            // Encrypt the campaign ID for secure usage in the response
            // $campaign->id = Crypt::encryptString($campaign->id);

            // dd($campaign_staff);
            // Return response
            return response()->json([
                'campaign' => $campaign,
                'images' => $images,
                'clientGroups' => $clientGroups,
                'groupPartners' => $groupPartners,
                'staff' => $campaign_staff
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid campaign ID'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $campaign = Campaigns::findOrFail($id);
        // dd($campaign);
        if ($campaign->status_id < 3) {
            $request->validate([
                'name' => 'required',
                'due_date' => 'required|date',
                'campaign_brief' => 'nullable|string',
                'additional_images.*' => 'nullable|mimes:jpeg,png,jpg,mp4,pdf,jfif|max:51200',
                'additional_images' => 'nullable|array',
                'thumbnail' => 'nullable|array',
                'thumbnail.*' => 'nullable|mimes:jpeg,png,jpg,jfif|max:10240',
            ]);
        } else {
            $campaign->status_id = (int) $request->status_id;
            $campaign->update();
            return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
        }

        // dd($request->all());
        if ($request->hasFile('additional_images')) {
            $thumbnails = $request->file('thumbnail') ?? [];
            $thumbnailIndex = 0;
            foreach ($request->file('additional_images') as $key => $file) {
                $extension = $file->getClientOriginalExtension();
                $isVideoOrPdf = in_array($extension, ['mp4', 'pdf']);
                if ($isVideoOrPdf) {
                    $thumbnail = $thumbnails[$thumbnailIndex] ?? null;
                    if (!$thumbnail) {
                        return back()->withErrors([
                            'thumbnail' => "Thumbnails are required for video or PDF files (File: {$file->getClientOriginalName()})."
                        ])->withInput();
                    }
                    $thumbnailValidation = Validator::make(
                        ['thumbnail' => $thumbnail],
                        ['thumbnail' => 'required|mimes:jpeg,png,jpg,jfif|max:10240']
                    );
                    if ($thumbnailValidation->fails()) {
                        return back()->withErrors($thumbnailValidation->errors())->withInput();
                    }
                    $thumbnailIndex++;
                }
            }
        }

        Log::info('Incoming request for image update', ['request_data' => $request->all(),]);

        $campaign = Campaigns::findOrFail($id);
        if ($request->hasFile('additional_images')) {
            $thumbnails = $request->file('thumbnail') ?? [];
            $thumbnailIndex = 0;
            foreach ($request->file('additional_images') as $key => $file) {
                try {
                    $extension = $file->getClientOriginalExtension();
                    $isVideoOrPdf = in_array($extension, ['mp4', 'pdf']);
                    $thumbnail = $thumbnails[$thumbnailIndex] ?? null;
                    $image = new Image();
                    $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $filePath = 'images/' . $randomName;
                    $this->uploadToS3($file, $filePath);
                    $extension = $file->getClientOriginalExtension();
                    $file_type = in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' : ($extension === 'pdf' ? 'document' : 'video');
                    $thumbnailPath = null;
                    if ($isVideoOrPdf) {
                        if ($thumbnail) {
                            $thumbnailName = 'thumb_' . Str::random(10) . '.' . $thumbnail->getClientOriginalExtension();
                            $thumbnailPath = 'images/' . $thumbnailName;
                            $this->uploadToS3($thumbnail, $thumbnailPath);
                        }
                    }
                    $image->path = $filePath;
                    $image->campaign_id = $campaign->id;
                    $image->file_name = $randomName;
                    $image->file_type = $file_type;
                    $image->thumbnail_path = $thumbnailPath;
                    $image->save();
                    Log::info('File uploaded successfully', ['file_id' => $image->id]);
                } catch (\Exception $e) {
                    Log::error('File upload error', ['error_message' => $e->getMessage()]);
                    return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
                }
            }
        }

        $campaign->name = $request->name;
        $campaign->description = $request->description;
        $campaign->due_date = $request->due_date;
        $campaign->client_id = (int) $request->client;
        $campaign->status_id = (int) $request->status_id;
        $campaign->client_group_id = (int) $request->clientGroup;
        $campaign->is_active = $request->has('active') ? 1 : 0;
        $campaign->update($request->all());
        if (isset($request->related_partner)) {
            // dd($request->related_partner);
            $camppart = CampaignPartner::where('campaigns_id', $id)->get();

            // Convert the existing campaign partners into an array of partner IDs for comparison
            $existingPartners = $camppart->pluck('partner_id')->toArray();

            // Loop through the incoming related partners and update or create them
            foreach ($request->related_partner as $partner) {
                CampaignPartner::updateOrCreate(
                    [
                        'campaigns_id' => $id,
                        'partner_id' => (int) $partner,
                    ],
                    [
                        'updated_at' => now(),
                    ]
                );
            }

            // Find partners that exist in the database but are not in the incoming request
            $partnersToDelete = array_diff($existingPartners, $request->related_partner);

            // Delete these partners
            if (!empty($partnersToDelete)) {
                CampaignPartner::where('campaigns_id', $id)
                    ->whereIn('partner_id', $partnersToDelete)
                    ->delete();
            }

        }
        if (isset($request->staff)) {
            // Step 1: Delete existing staff assignments for this campaign
            CampaignStaff::where('campaign_id', $id)->delete();

            // Step 2: Insert new staff assignments
            foreach ($request->staff as $staff) {
                CampaignStaff::create([
                    'campaign_id' => $id,
                    'staff_id' => (int) $staff
                ]);
            }
        }


        return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        //    $id = encrypt_decrypt($id, 'd');
        $campaign = Campaigns::findOrFail($id);
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully.');


        // return view('campaigns.index', compact('title', 'sideBar'));
    }

    public function destroyAsset($id)
    {
        try {
            $asset = Image::find($id);
            if (!$asset) {
                Log::warning("Asset deletion attempted for non-existent ID: {$id}");
                return response()->json([
                    'success' => false,
                    'message' => 'Asset not found.'
                ], 404);
            }
            if (Storage::disk('backblaze')->exists($asset->path)) {
                if (!Storage::disk('backblaze')->delete($asset->path)) {
                    Log::error("Failed to delete asset file: {$asset->path} for Asset ID: {$id}");
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to delete asset file from storage.'
                    ], 500);
                }
            } else {
                Log::warning("Asset file not found in storage: {$asset->path} for Asset ID: {$id}");
            }
            $asset->delete();
            Log::info("Asset deleted successfully: Asset ID: {$id}, Path: {$asset->path}");
            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            Log::error("Error deleting asset: Asset ID: {$id}. Exception: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the asset. Please try again later.'
            ], 500);
        }
    }

    public function assetsView(string $id)
    {

        $categories = Category::where('is_active', 1)->get();
        $assets = AssetType::where('is_active', 1)->get();

        $previousUrl = URL::previous();
        $categories = Category::where('is_active', 1)->get();
        $image = Image::findOrFail($id);
        $campaigns = Campaigns::with('image')->where('id', $image->campaign_id)->get();
        $partners = CampaignPartner::with([
            'campaign.client',
            'partner.roles' => function ($query) {
                $query->where('role_level', 6); // Filter roles with role_level = 6
            }
        ])
            ->where('campaigns_id', $campaigns->first()->id)
            ->whereHas('partner', function ($query) {
                $query->where('is_active', 1); // Filter partners with is_active = 1
            })
            ->get();
        // Enable query logging
        DB::enableQueryLog();
        // dd($campaigns);
        $client = Client::where('id', $campaigns->first()->client_id)->first();
        $post = Post::create([
            'title' => $campaigns[0]['name'],
            'description' => $campaigns[0]['description'],
            'image_id' => $image->id,
        ]);
        $post_id = $post->slug;

        // Get the query log
        $queries = DB::getQueryLog();

        // Log the query to the Laravel log
        logger()->info('SQL Query:', $queries);

        // dd($partners);
        $returnUrl = 'campaigns';
        if (str_contains(parse_url($previousUrl, PHP_URL_PATH), 'home')) {
            $returnUrl = 'home';
        }

        if ($image && $campaigns->isNotEmpty()) {
            $post_id = $post_id;
            $image_path = Storage::disk('backblaze')->url($image->path);
            $fileType = $image->file_type;
            $file_name = $image->file_name;
            $fileSize = Storage::disk('backblaze')->size($image->path); // Size in bytes
            $fileExtension = pathinfo($image->path, PATHINFO_EXTENSION); // Get the file extension
            $fileSizeKB = round($fileSize / 1024, 2);
            $campDescription = $campaigns[0]['description'];
            $campStatus = $campaigns[0]['is_active'];
            $campId = $campaigns[0]['id'];
            $title = $campaigns[0]['name'];
            $image_id = $id;
        } else {
            $image_path = null;
            $filePath = null;
            $fileType = null;
            $fileExtension = null;
            $fileSizeKB = null;
            $campDescription = "";
            $campStatus = null;
            $campId = "";
            $post_id = "";
            $image_id = null;
        }

        return view('campaigns.asset_view', compact('image_id', 'title', 'file_name', 'fileType', 'post_id', 'returnUrl', 'campaigns', 'image_path', 'categories', 'fileExtension', 'fileSizeKB', 'campDescription', 'campStatus', 'campId', 'categories', 'assets', 'partners', 'client'));
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

        return $twitterShareUrl;
    }

    public function getClientGroups($clientId)
    {
        $clientGroups = ClientGroup::where('client_id', $clientId)->get();
        return response()->json($clientGroups);
    }


    public function getPartners($clientId)
    {
        $partners = ClientPartner::with([
            'partner' => function ($query) {
                $query->where('is_active', 1); // Filter users with is_active = 1
            }
        ])
            ->where('client_id', $clientId)
            ->get();
        // dd($partners);
        return response()->json($partners);
    }

    public function showPdf($filename)
    {
        $filePath = "path-to-your-pdf/$filename";
        $fileContent = Storage::disk('backblaze')->url($filePath);

        return response($fileContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline'); // Forces the browser to display in an inline viewer
    }

    public function fetchImages(Campaigns $campaign)
    {
        $images = $campaign->image->map(function ($image) {
            return [
                'id' => $image->id,
                'name' => $image->file_name,
                'url' => Storage::disk('backblaze')->url($image->path),
                'file_type' => $image->file_type,
            ];
        });

        return response()->json($images);
    }


}
