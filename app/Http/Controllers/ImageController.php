<?php

namespace App\Http\Controllers;

use App\Models\Image;
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
        $images = Image::all(['file_name', 'path']);

        // Retrieve the URLs for each image
        $imageUrls = $images->map(function ($image) {
            return [
                'name' => $image->file_name,
                'path' => $image->path,
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
        // Validate the incoming request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Log the incoming request
        Log::info('Incoming request for image upload', [
            'request_data' => $request->all(),
        ]);

        // Store the uploaded file in Backblaze B2
        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');

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
}
