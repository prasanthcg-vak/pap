<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="{{ $post['title'] }}">
    <meta property="og:description" content="{{ $post['description'] }}">
    @if($post['file_type'] == 'image')
    <meta property="og:image" content="{{ $post['file_path'] }}"> <!-- Ensure the URL is absolute -->
    <meta property="og:url" content="{{ route('posts.share', $post['slug']) }}">
    @elseif($post['file_type'] == 'video')
    <meta property="og:image" content="{{ $post['file_path'] }}"> <!-- Thumbnail for the video -->
    <meta property="og:video" content="{{ $post['file_path'] }}"> <!-- Video file URL -->
    <meta property="og:video:secure_url" content="{{ $post['file_path'] }}"> <!-- Secure HTTPS URL -->
    <meta property="og:video:type" content="video/mp4"> <!-- MIME type of the video -->
    <meta property="og:video:width" content="1280"> <!-- Optional: Width of the video -->
    <meta property="og:video:height" content="720"> <!-- Optional: Height of the video -->
    <meta property="og:url" content="{{ route('posts.share', $post['slug']) }}">
    @else
    <meta property="og:image" content="{{ $post['file_path'] }}"> <!-- Thumbnail for the PDF -->
    <meta property="og:url" content="{{ $post['file_path'] }}"> <!-- URL to the PDF file -->
    <meta property="og:type" content="article"> <!-- `article` works for documents -->
    <meta property="og:document" content="{{ $post['file_path'] }}">
    @endif
    <meta property="og:type" content="article">
    <title>{{ $post['title'] }}</title>
</head>
<body>
    <h1>{{ $post['title'] }}</h1>
    @if($post['file_type'] == 'image')
     <img src="{{ $post['file_path'] }}" alt="Post Image">
    @elseif($post['file_type'] == 'video')
    <video controls width="600">
        <source src="{{ $post['file_path'] }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    @else
        <a href="{{ $post['file_path'] }}" target="_blank" rel="noopener">Download PDF</a>
    @endif

    <p>{{ $post['description']}}</p>
</body>
</html>
