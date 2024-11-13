<?php

namespace App\Http\Controllers;

use App\Models\Campaigns;
use App\Models\Category;
use App\Models\ClientPartner;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Models\UserPermissions;
use App\Models\CampaignPartner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
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


        $campaigns = Campaigns::with('image')->where('is_active', 1)->get();
        $partners = ClientPartner::with(['client', 'partner'])
            ->where('client_id', $authId)
            ->get();

        // dd($partners);
        $sideBar = 'dashboard';
        $title = 'dashboard';
        return view('campaigns.index', compact('campaigns', 'partners'));
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
        Log::info('Incoming request for image upload', [
            'request_data' => $request->all(),
        ]);
        $image = new Image();
        // Store the uploaded file in Backblaze B2
        if ($request->hasFile('cover_image')) {

            try {
                $file = $request->file('cover_image');

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
        // dd($image->id);
        // dd("test");
        $request->validate(
            [
                'name' => 'required',
                'description' => 'required',
                'due_date' => 'required',
                // 'status_id' => 'required',
            ]
        );

        $data = new Campaigns();
        $data->name = $request->name;
        $data->description = $request->description;
        $data->due_date = $request->due_date;
        $data->status_id = null;
        $data->image_id = $image->id;
        $data->is_active = 1;
        $data->save();
        $id = $data->id;



        if (isset($request->related_partner)) {
            foreach ($request->related_partner as $partner) {
                $data = new CampaignPartner();
                $data->campaigns_id = $id;
                $data->partner_id = $partner;
                $data->save();
            }
        }

        return redirect()->route('campaigns.index')->with('success', 'campaigns Created successfully.');
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
            'cover_image' => 'nullable|image|mimes:jpeg,png|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        Log::info('Incoming request for image upload', [
            'request_data' => $request->all(),
        ]);
        $image = new Image();
        // Store the uploaded file in Backblaze B2
        if ($request->hasFile('cover_image')) {

            try {
                $file = $request->file('cover_image');

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
        $campaign->update($request->all());

        if ($request->hasFile('cover_image')) {

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
    public function assets_view(string $id)
{
    $categories = Category::where('is_active', 1)->get();

    $campaigns = Campaigns::with('image')->where('is_active', 1)->where('id', $id)->first();
    if ($campaigns && $campaigns->image) {
        $image_path = Storage::disk('backblaze')->url($campaigns->image->path);

        // Get file type and size
        $fileType = Storage::disk('backblaze')->mimeType($campaigns->image->path);
        $fileSize = Storage::disk('backblaze')->size($campaigns->image->path); // Size in bytes
        $fileExtension = pathinfo($campaigns->image->path, PATHINFO_EXTENSION); // Get the file extension


        // Convert file size to KB for readability
        $fileSizeKB = round($fileSize / 1024, 2);
    } else {
        $image_path = null;
        $fileType = null;
        $fileSizeKB = null;
    }

    return view('campaigns.asset_view', compact('campaigns', 'image_path', 'categories', 'fileExtension', 'fileSizeKB'));
}


}
