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
    <title>View Post</title>

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/post.css') }}">

    <!-- Bootstrap 5.2 CSS cdn link-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

</head>

<body>

    <!-- ========== Start view-post ========== -->
    <section class="view-post">
        <div class="container">
            <div class="view-post-wrapper">
                <!-- Navbar -->
                <nav class="navbar">
                    <div class="container-fluid">
                        <a class="navbar-brand">
                            <img src="{{ asset('/assets/images/NewCMLogo2024.svg') }}" alt="logo">
                        </a>
                    </div>
                </nav>
                <!-- Heading -->
                <h1>{{ $post['title'] }}</h1>
                <!-- View-post-contents -->
                <div class="col-lg-12 View-post-contents">
                    <div class="view-post-image">
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
                    <!-- <img src="assets/images/How-AI-Generative-Chatbots-Boost-Business-Efficiency-and-Profitability-compress.jpg"
                            alt="post-image"> -->
                    </div>
                    <!-- <h4 class="sub-heading mt-3">Lorem ipsum dolor sit amet consectetur</h4> -->
                    <p class="mt-3">{{ $post['description']}}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- ========== End view-post ========== -->




    <!-- Bootstrap 5.2 JS cdn link-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
