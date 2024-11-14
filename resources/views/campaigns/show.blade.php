@extends('layouts.app')

@section('content')

<style>
    .img-container .image-wrapper {
    height: 150px; /* Set a fixed height for consistent display */
    overflow: hidden;
}

.img-container .image-wrapper img {
    object-fit: cover;
    max-height: 100%;
    max-width: 100%;
}

    </style>

<div class="CM-main-content">
    <div class="container-fluid p-0">
        <!-- Table -->
        <div class="campaingn-table pb-3 common-table">

            <!-- campaigns-contents -->
            <div class="col-lg-12 task campaigns-contents">
                <div class="campaigns-title">
                    <h3>{{$campaign->name}}</h3>
                </div>
                
             

            </div>
            <div>
                {{$campaign->description}}
            </div>
<hr>
<div class="row img-container">
    @foreach($imageUrls as $index => $image)
        <div class="col-3 mb-3">
            <div class="image-wrapper d-flex justify-content-center align-items-center">
                <img src="{{ $image['url'] }}" class="img-fluid" alt="">
            </div>
        </div>

        {{-- After the 4th image, hide the rest initially --}}
        @if ($index == 3)
            @break
        @endif
    @endforeach
</div>

{{-- Hidden container for the remaining images --}}
<div id="moreImages" class="row img-container d-none" style="max-height: 300px; overflow-y: auto;">
    @foreach($imageUrls->slice(4, 10) as $image)
        <div class="col-3 mb-3">
            <div class="image-wrapper d-flex justify-content-center align-items-center">
                <img src="{{ $image['url'] }}" class="img-fluid" alt="">
            </div>
        </div>
    @endforeach
</div>

{{-- See More Button --}}
<button id="seeMoreBtn" class="btn mt-3 float-end">See More</button>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- campaigns-contents -->
            
        </div>
        <!-- Table -->
    </div>
</div>

<script>
    document.getElementById('seeMoreBtn').addEventListener('click', function() {
    const moreImages = document.getElementById('moreImages');
    moreImages.classList.toggle('d-none');
    
    // Change button text depending on visibility
    this.textContent = moreImages.classList.contains('d-none') ? 'See More' : 'Show Less';
});

</script>

@endsection