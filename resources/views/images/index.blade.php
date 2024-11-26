@extends('layouts.app')

@section('content')
    <div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

        <h2>Image Gallery</h2>
        <!-- Button to open the image upload page -->
        <div class="mb-4">
            <a href="{{ route('images.create') }}" class="btn btn-primary">Upload New Image</a>
        </div>
        <div class="row">
            @foreach($imageUrls as $image)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <!-- Display media thumbnail -->
                        @php
                            $thumbnail = match($image['type']) {
                                'image' => $image['url'],
                                'video' => asset('assets/images/video.png'),
                                default => asset('assets/images/document.png'),
                            };
                        @endphp

                        <img src="{{ $thumbnail }}" class="card-img-top img-thumbnail" style="max-height: 200px;" alt="{{ $image['name'] }}">

                        <div class="card-body text-center">
                            <!-- View button opens the modal with the full image -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#viewModal" onclick="showImage('{{ $image['url'] }}')">View</button>
                            <!-- Delete form sends a DELETE request -->
                            <form id="Model-Form" action="{{ route('images.delete') }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="path" value="{{ $image['path'] }}">
                                <button type="submit" class="btn btn-danger mt-2">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal for viewing the full-size image -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Image</h5>
                    <button type="button" class="close" id="cancel" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <!-- Full-size image will be shown here -->
                    <img id="modalImage" src="" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function showImage(url) {
            document.getElementById('modalImage').src = url;
        }
    </script>
@endsection
