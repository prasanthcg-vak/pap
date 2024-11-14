@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Image Gallery</h2>
        <div class="row">
            @foreach($imageUrls as $image)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ $image['url'] }}" class="card-img-top img-thumbnail" style="max-height: 200px;" alt="{{ $image['name'] }}">
                        <div class="card-body text-center">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#viewModal" onclick="showImage('{{ $image['url'] }}')">View</button>
                            <form action="{{ route('images.delete') }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="path" value="{{ $image['path'] }}">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- Loop through image URLs -->
        </div>
    </div>

    <!-- Modal for viewing image -->
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
                    <img id="modalImage" src="" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    @endsection
    @section('script')
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->
    <script>
        function showImage(url) {
            document.getElementById('modalImage').src = url;
        }
    </script>
@endsection
