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
use Illuminate\Http\Request;
use App\Models\UserPermissions;
use App\Models\CampaignPartner;
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
        // dd($client_id);
        $groups = [];
        if ($role_level > 3) {
            $groups = ClientGroup::where("client_id", $client_id)->get();
            // dd($groups);
        }
        // $campaigns = Campaigns::with('image')->where('is_active', 1)->get();
        $campaigns = Campaigns::with('image')->get();

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

        // dd($request->all());

        $request->validate(
            [
                'name' => 'required',
                'description' => 'required',
                'due_date' => 'required',
                // 'status_id' => 'required',
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

        $images = Image::where('campaign_id', $id)->get(['id', 'file_name', 'path', 'file_type']);

        // Retrieve the URLs for each image
        $imageUrls = $images->map(function ($image) {
            return [
                'image_id' => $image->id,
                'name' => $image->file_name,
                'path' => $image->path,
                'image_type' => $image->file_type,
                'url' => Storage::disk('backblaze')->url($image->path) // Generate the public URL
            ];
        });
        $partners = ClientPartner::all(); // Assuming you have a Partner model
        return view('campaigns.show', compact('campaign', 'partners', 'tasks', 'imageUrls'));
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
            'additional_images' => 'nullable|image|mimes:jpeg,png|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png|max:2048',
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
        $campaign->is_active = $request->has('active') ? 1 : 0;

        $campaign->update($request->all());
        // dd($campaign);

        if ($request->hasFile('additional_images')) {

            $campaign->image_id = $image->id;
            $campaign->save();
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
            'description' => $campaigns[0]['description'],
            'file_path' => Storage::disk('backblaze')->url($image->path),
        ]);
        $post_id = $post->id;

        // Get the query log
        $queries = DB::getQueryLog();

        // Log the query to the Laravel log
        logger()->info('SQL Query:', $queries);

        // dd($partners);
        $returnUrl = 'campaigns';
        if (str_contains(parse_url($previousUrl, PHP_URL_PATH), 'home')) {
            $returnUrl = 'home';
        }

        // $campaigns = Campaigns::with('image')->where('is_active', 1)->where('id', $id)->first();
        if ($image && $campaigns->isNotEmpty()) {
            $post_id = $post_id;
            $image_path = Storage::disk('backblaze')->url($image->path);
            // Get file type and size
            $fileType = Storage::disk('backblaze')->mimeType($image->path);
            $fileSize = Storage::disk('backblaze')->size($image->path); // Size in bytes
            $fileExtension = pathinfo($image->path, PATHINFO_EXTENSION); // Get the file extension
            $fileSizeKB = round($fileSize / 1024, 2);
            $campDescription = $campaigns[0]['description'];
            $campStatus = $campaigns[0]['is_active'];
            $campId = $campaigns[0]['id'];
        } else {
            $image_path = null;
            $fileType = null;
            $fileExtension = null;
            $fileSizeKB = null;
            $campDescription = "";
            $campStatus = null;
            $campId = "";
            $post_id = "";
        }

        return view('campaigns.asset_view', compact('post_id', 'returnUrl', 'campaigns', 'image_path', 'categories', 'fileExtension', 'fileSizeKB', 'campDescription', 'campStatus', 'campId', 'categories', 'assets', 'partners'));
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


}
