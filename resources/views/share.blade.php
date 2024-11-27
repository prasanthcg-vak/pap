<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="{{ $post->description }}">
    <meta property="og:description" content="{{ $post->description }}">
    <meta property="og:image" content="{{ $post->file_path }}"> <!-- Ensure the URL is absolute -->
    <meta property="og:url" content="{{ route('posts.share', $post->id) }}">
    <meta property="og:type" content="article">
    <title>{{ $post->description }}</title>
</head>
<body>
    <h1>{{ $post->description }}</h1>
    <img src="{{ $post->file_path }}" alt="Post Image">
</body>
</html>
