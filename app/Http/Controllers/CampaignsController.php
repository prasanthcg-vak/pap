<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Campaigns;
use App\Models\Category;
use App\Models\Client;
use App\Models\ClientGroup;
use App\Models\ClientGroupPartners;
use App\Models\ClientPartner;
use App\Models\Status;
use App\Models\Tasks;
use App\Models\Post;
use App\Models\Image;
use App\Models\UserPermissions;
use App\Models\CampaignPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

        // dd($client_id);
        $groups = [];
        // if ($role_level > 3) {
        $groups = ClientGroup::where("client_id", $client_id)->get();
        // dd($groups);
        // }
        // $campaigns = Campaigns::with('image')->where('is_active', 1)->get();
        if ($role_level < 4) {
            $campaigns = Campaigns::with('images', 'client', 'group', 'tasks')->get();
            // dd($campaigns[0]->images);

        } elseif ($role_level == 4) {
            $campaigns = Campaigns::with('images', 'client', 'group')->where("client_id", $client_id)->get();
        } elseif ($role_level == 5) {
            $campaigns = Campaigns::with('images', 'client', 'group')->where("client_id", $client_id)->where("Client_group_id", $group_id)->get();

        } elseif ($role_level == 6) {
            $campaigns = Campaigns::with('images', 'client', 'group', 'partner')
                ->whereHas('partner', function ($query) use ($authId) {
                    $query->where('partner_id', $authId);
                })
                ->get();
            // dd($campaigns);
        }
        // $campaigns = Campaigns::with('image', 'client', 'group')->get();

        // dd($campaigns[3]->group->name);
        $partners = ClientPartner::with(['client', 'partner'])
            ->where('client_id', $authId)
            ->get();


        $clients = Client::get();
        // dd($partners);
        $sideBar = 'dashboard';
        $title = 'dashboard';
        return view('campaigns.index', compact('campaigns', 'partners', 'clients', 'role_level', 'groups', 'client_id'));
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
            'additional_images.*' => 'nullable|mimes:jpeg,png,jpg,mp4,pdf|max:51200', // 50 MB limit
            'additional_images' => 'nullable|array',
            'thumbnail' => 'nullable|array',
            'thumbnail.*' => 'file|mimes:jpeg,png,jpg|max:10240', // 10 MB limit for thumbnails
        ]);

        // Step 2: Validate thumbnails for specific file types
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $key => $file) {
                $extension = $file->getClientOriginalExtension();
                $isVideoOrPdf = in_array($extension, ['mp4', 'pdf']);

                // Check if the corresponding thumbnail is provided
                $thumbnail = $request->file('thumbnail')[$key] ?? null;

                if ($isVideoOrPdf && !$thumbnail) {
                    return back()->withErrors([
                        'thumbnail' => "Thumbnails are required for video or PDF files (File: {$file->getClientOriginalName()})."
                    ])->withInput();
                }
            }
        }
        
        // Log incoming request
        Log::info('Incoming campaign request', ['request_data' => $request->all()]);

        // Create Campaign entry
        $data = new Campaigns();
        $data->name = $request->name;
        $data->description = $request->description;
        $data->due_date = $request->due_date;
        $data->status_id = null;
        $data->client_id = (int) $request->client;
        $data->client_group_id = (int) $request->clientGroup;
        $data->is_active = 1;
        $data->save();

        // Upload additional images and thumbnails
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $key => $file) {
                $thumbnail = $request->file('thumbnail')[$key] ?? null;

                try {
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
                    if ($thumbnail) {
                        $thumbnailName = 'thumb_' . Str::random(10) . '.' . $thumbnail->getClientOriginalExtension();
                        $thumbnailPath = 'images/' . $thumbnailName;
                        $this->uploadToS3($thumbnail, $thumbnailPath);
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
                'additional_images.*' => 'nullable|mimes:jpeg,png,jpg,mp4,pdf|max:51200',
                'additional_images' => 'nullable|mimes:jpeg,png,jpg,mp4,pdf|max:51200', // 50 MB limit
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
        return view('campaigns.show', compact('campaign', 'partners', 'tasks', 'imageUrls'));
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
        ->where('campaign_id',$id)
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

    return view('campaigns.list', compact('assets','campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $campaign = Campaigns::findOrFail($id);
        $partners = ClientPartner::all(); // Assuming you have a Partner model
        return view('campaigns.edit', compact('campaign', 'partners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'due_date' => 'required|date',
            'campaign_brief' => 'nullable|string',
            'additional_images.*' => 'nullable|mimes:jpeg,png,jpg,mp4,pdf|max:51200',
            'additional_images' => 'nullable|mimes:jpeg,png,jpg,mp4,pdf|max:51200', // 50 MB limit

        ]);

        Log::info('Incoming request for image upload', [
            'request_data' => $request->all(),
        ]);
        $image = new Image();
        // Store the uploaded file in Backblaze B2
        if ($request->hasFile('additional_images')) {

            try {
                $file = $request->file('additional_images');

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

                    // Store the image details in the database

                    $image->path = $filePath; // Assuming you have a 'path' column in your 'images' table
                    $image->file_name = $randomName; // Store the random name if needed
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

        $campaign = Campaigns::findOrFail($id);
        $campaign->name = $request->name;
        $campaign->description = $request->description;
        $campaign->due_date = $request->due_date;
        $campaign->client_id = (int) $request->client;
        $campaign->client_group_id = (int) $request->clientGroup;
        // $data->image_id = $image->id;
        $campaign->is_active = $request->has('active') ? 1 : 0;

        $campaign->update($request->all());

        if ($request->hasFile('additional_images')) {

            $campaign->image_id = $image->id;
            $campaign->save();
        }

        if (isset($request->related_partner)) {
            foreach ($request->related_partner as $partner) {
                CampaignPartner::updateOrCreate(
                    [
                        'campaigns_id' => $id, // Matching condition
                        'partner_id' => (int) $partner,
                    ],
                    [
                        // Any additional data to update
                        'updated_at' => now(),
                    ]
                );
            }
        }

        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $additionalImage) {
                $additionalImagePath = $additionalImage->store('additional_images');
                $campaign->additional_images()->create(['path' => $additionalImagePath]);
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

    public function assetsView(string $id)
    {

        $categories = Category::where('is_active', 1)->get();
        $assets = AssetType::where('is_active', 1)->get();

        $previousUrl = URL::previous();
        $categories = Category::where('is_active', 1)->get();
        $image = Image::findOrFail($id);
        $campaigns = Campaigns::with('image')->where('id', $image->campaign_id)->get();
        $partners = CampaignPartner::where('campaigns_id', $image->campaign_id)->whereHas('partner.roles', function ($query) {
            $query->where('role_level', 6);
        })->with('partner')->get();

        // Enable query logging
        DB::enableQueryLog();

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

        return view('campaigns.asset_view', compact('image_id','title','file_name', 'fileType', 'post_id', 'returnUrl', 'campaigns', 'image_path', 'categories', 'fileExtension', 'fileSizeKB', 'campDescription', 'campStatus', 'campId', 'categories', 'assets', 'partners'));
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

    public function getPartners($groupId)
    {
        $partners = ClientGroupPartners::with('user')->where('group_id', $groupId)->get();
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
