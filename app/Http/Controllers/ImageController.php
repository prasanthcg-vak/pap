<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Tasks;
use App\Models\Campaigns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\S3\S3Client;
use Exception;

class ImageController extends Controller
{
    public function index()
    {
        // Fetch file names and paths from the database
        $images = Image::all(['file_name', 'path','file_type']);

        // Retrieve the URLs for each image
        $imageUrls = $images->map(function ($image) {
            return [
                'name' => $image->file_name,
                'path' => $image->path,
                'type' => $image->file_type,
                'url' => Storage::disk('backblaze')->url($image->path) // Generate the public URL
            ];
        });
        return view('images.index', compact('imageUrls'));
    }

    public function create()
    {
        return view('images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,mp4,pdf|max:51200', // 50 MB limit
        ]);

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $randomName = uniqid() . '.' . $extension;
        $filePath = 'images/' . $randomName;
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
    
            $upload = new Image(); 
            $upload->path = $filePath;
            $upload->file_name = $randomName;
            $upload->file_type = $file_type;
            $upload->save();
    
            Log::info('File uploaded successfully', [
                'database_file_id' => $upload->id,
            ]);

            Log::info('Uploaded File Path : ', ['file_path' => $result['ObjectURL']]);
            return redirect()->route('images.index')->with('success', 'File uploaded successfully.');
    
        } catch (Aws\Exception\AwsException $e) {
            Log::error('Backblaze upload failed', [
                'error_message' => $e->getAwsErrorMessage(),
            ]);
    
            return response()->json([
                'error' => 'Backblaze upload failed: ' . $e->getAwsErrorMessage(),
            ], 500);
        }
    }

    public function storeOld(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,mp4,avi,mov,pdf|max:20480',
        ]);

        // Log the incoming request
        Log::info('Incoming request for image upload', [
            'request_data' => $request->all(),
        ]);

        // Store the uploaded file in Backblaze B2
        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $randomName = uniqid() . '.' . $extension; // Generate a unique file name
                $filePath = 'images/' . $randomName; // Define storage path

                // Log the file details
                Log::info('File details', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);

                // $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                // $filePath = 'images/' . $randomName;

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
                    $image = new Image();
                    $image->path = $filePath; // Assuming you have a 'path' column in your 'images' table
                    $image->file_name = $randomName; // Store the random name if needed
                    $image->save();

                    // Log successful storage
                    Log::info('Image uploaded successfully', [
                        'database_image_id' => $image->id,
                    ]);

                    // Redirect back to index with success message
                    return redirect()->route('images.index')->with('success', 'Image uploaded successfully.');

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

        return redirect()->back()->withErrors(['error' => 'File upload failed.']);
    }

    public function destroy(Request $request)
    {
        // Get the image ID from the request (assuming you're passing image ID)
        $imageId = $request->input('image_id');

        // Find the image record in the database
        $image = Image::find($imageId);

        if (!$image) {
            return redirect()->back()->with('error', 'Image not found.');
        }

        // Get the file path from the image record
        $path = $image->file_path; // Adjust this to the correct column storing the file path

        // Delete the image record from the database
        $image->delete();

        // Now delete the file from Backblaze storage
        if (Storage::disk('backblaze')->exists($path)) {
            if (Storage::disk('backblaze')->delete($path)) {
                return redirect()->back()->with('success', 'Image and data deleted successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to delete the image from storage.');
            }
        }

        return redirect()->back()->with('error', 'File not found in storage.');
    }

    public function list_all_images()
    {
        // List all files in 'images/' folder
        $files = Storage::disk('backblaze')->files('images/');

        // Filter the files to include only image types
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Define allowed image extensions
        $imageUrls = array_filter($files, function ($file) use ($imageExtensions) {
            return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $imageExtensions);
        });

        // Map the image files to an array with URL and path
        $imageUrls = array_map(function ($file) {
            return [
                'name' => basename($file),
                'path' => $file,
                'url' => Storage::disk('backblaze')->url($file) // Generate public URL
            ];
        }, $imageUrls);

        return view('images.index', compact('imageUrls'));
    }

    public function listCampaignImages(Request $request)
    {
        // Fetch campaigns along with their related images
        $assets = Image::with('campaign') // Eager load the campaign relationship
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'file_name' => $image->file_name,
                    'image_type' => pathinfo($image->file_name, PATHINFO_EXTENSION), // Image type (extension)
                    'image' => Storage::disk('backblaze')->url($image->path) ?? null, // Assuming the image is stored on Backblaze
                    'campaign_name' => $image->campaign ? $image->campaign->name : 'No Campaign', // Access the campaign name
                    'campaign_id' => $image->campaign_id, // Access the campaign_id directly
                ];
            });
    
        // Return the view with the assets (campaign images)
        return view('images.list', compact('assets'));
    }    
    

}
