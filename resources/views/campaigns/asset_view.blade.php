@extends('layouts.app')

@section('content')
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!--Single Image Container-->
            <div class="single-img-con">
                <div class="sic-wrap">
                    <div class="sic-header d-flex justify-content-between align-items-center">
                        <h3>ASSET #01</h3>
                        <p class="status green">Active</p>
                    </div>

                    <div class="sic-img-info">
                        <ul class="list-unstyled p-0 m-0 d-flex flex-column flex-md-row align-items-md-center flex-wrap">
                            <li>
                                <span>Type: {{ $fileExtension }}</span>
                            </li>
                            <li> <span>Dimensions: 1200px (w) 628px (h)</span></li>
                            <li> <span> Size: {{ $fileSizeKB }}kb</span></li>
                        </ul>
                    </div>

                    <div class="sic-src-wrap">
                        <img class="w-50" src="{{ $image_path }}" alt="">
                    </div>

                <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                    <div class="sic-btn">
                        @if($returnUrl == 'home')
                            <button class="btn cancel-btn" onclick="window.location.href='{{ route('home') }}'">
                                Cancel
                            </button>
                        @else
                            <button class="btn cancel-btn" onclick="window.location.href='{{ route('campaigns.show', ['id' => $campId]) }}'">
                                Cancel
                            </button>
                        @endif
                    </div>
                    <div class="sic-btn">
                        <button class="btn create-task" data-bs-toggle="modal" data-bs-target="#createTask">
                            create task
                        </button>
                    </div>
                    @if($campStatus === 1)                        
                        <div class="sic-btn">
                            {{-- <button class="btn link-asset"  onclick="openLinkModal('{{ $image_path }}','{{ $campDescription }}')">
                                link asset
                            </button> --}}
                            <button class="btn link-asset" 
                                    data-url="{{ $image_path }}" 
                                    data-description="{{ $campDescription }}">
                                link asset
                            </button>
                        </div>
                    @endif
                    <div class="sic-btn">
                        <button class="btn download" data-url="{{ $image_path }}">
                            download
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--End Single Image Container-->
    </div>
</div>

    <!-- Link Asset Modal -->
    <div class="modal fade linkAsset-modal" id="linkAssetModal" tabindex="-1" aria-labelledby="linkAssetModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="linkAssetModalLabel">Link Asset</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0">
                        <div class="col-md-12 mb-4">
                            <h4 class="bold-labels" for="">public access</h4>
                            {{-- <input type="text" value="" id="myInput">
                        <button onclick="copyToClipboard()">Copy text</button> --}}

                            <div class="web-link-col">
                                <div class="row m-0 align-items-center">
                                    <div class="col-9">
                                        <div class="check-list">
                                            <span>
                                                Web Link : <span id="assetLink" style="color:#EB8205"></span>
                                            </span>
                                        </div>
                                    </div>
                                    {{-- <div class="col-3 text-end">
                                    <button class=" btn copy-web-link p-0" onclick="copyToClipboard()">
                                        <svg width="39" height="39" viewBox="0 0 39 39" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M31.6247 8.62496V23.9583C31.6247 28.1925 28.1922 31.625 23.958 31.625H8.62467C4.39049 31.625 0.958008 28.1925 0.958008 23.9583V8.62496C0.958008 4.39077 4.39049 0.95829 8.62467 0.95829H23.958C28.1922 0.95829 31.6247 4.39077 31.6247 8.62496ZM3.83301 8.62496V23.9583C3.83301 25.2291 4.33784 26.4479 5.23645 27.3465C6.13507 28.2451 7.35385 28.75 8.62467 28.75H23.958C26.6044 28.75 28.7497 26.6047 28.7497 23.9583V8.62496C28.7497 5.97859 26.6044 3.83329 23.958 3.83329H8.62467C5.97831 3.83329 3.83301 5.97859 3.83301 8.62496Z"
                                                fill="#EB8205" />
                                            <path
                                                d="M37.3747 12.9375C36.5851 12.9478 35.9475 13.5854 35.9372 14.375V29.7083C35.9267 33.1442 33.1439 35.9269 29.708 35.9375H14.3747C13.5808 35.9375 12.9372 36.5811 12.9372 37.375C12.9372 38.1689 13.5808 38.8125 14.3747 38.8125H29.708C34.7361 38.8125 38.8122 34.7364 38.8122 29.7083V14.375C38.8019 13.5854 38.1643 12.9478 37.3747 12.9375Z"
                                                fill="#EB8205" />
                                        </svg>
                                    </button>
                                </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10 col-xl-6 mb-4">
                            <h4 class="bold-labels">social media access</h4>
                            <div class="parah">
                                <p>Click on the icon below and follow the instructions to post your banner
                                    to your social media account.</p>
                            </div>
                            <div class="social-links">
                                <ul>
                                    <li>
                                        <a id="linkedinShare" href="#" target="_blank">
                                            <i class="fa-brands fa-linkedin"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a id="facebookShare" href="#" target="_blank">
                                            <i class="fa-brands fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a id="twitterShare" href="#" target="_blank">
                                            <i class="fa-brands fa-x-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a id="redditShare" href="#" target="_blank">
                                            <i class="fa-brands fa-reddit-alien"></i>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade createTask-modal" id="createTask" tabindex="-1" aria-labelledby="ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Create Task
                    </h1>
                    {{-- <p class="status green">Active</p> --}}
                    <span class="btn-close" data-bs-dismiss="modal" id="cancel" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form id="Model-Form" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row m-0">
                            <!-- Campaign Dropdown -->
                            <div class="col-xl-4 col-md-6">
                                <select class="form-select" id="campaign-select" name="campaign_id" required
                                    aria-label="Default select example">
                                    {{-- <option value="" >Select Campaign</option> --}}
                                    @foreach ($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}" >{{ $campaign->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Partner Dropdown -->
                            <div class="col-xl-4 col-md-6 mt-md-0 mt-4">
                                <select class="form-select" id="partner-select" name="partner_id" required
                                    aria-label="Default select example">
                                    <option value="" >Select Partner</option>
                                @foreach ($partners as $partner)
                                <option value="{{$partner->partner_id}}" >{{$partner->partner->name}}</option>

                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-xl-4">
                                <input type="text" name="name" id="" required placeholder="Task Name">
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-xl-4">
                                <label for="">Date Required</label>
                                <div class="input-wrap">
                                    <input type="date" name="date_required" id="datepicker" required
                                        placeholder="Date Required">

                                    <div class="form-group">
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <div> Urgent</div>
                                                <input type="checkbox" name="task_urgent" />
                                                <span></span>

                                            </label>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">

                                <select class="form-select" name="category_id" required aria-label="Default select example">
                                    <option value="" selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }} ">
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">

                                <select class="form-select" name="asset_id" required aria-label="Default select example">
                                    <option value="" selected>Select Asset</option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }} ">
                                            {{ $asset->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-xl-3 p-xl-0 sizing-input">
                                <div class="input-wrap">
                                    <input type="number" name="size_width" id="size_width" required
                                        placeholder="Size (Width)">
                                    <input type="number" name="size_height" id="size_height" required
                                        placeholder="Size (Height)">
                                </div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-md-12">
                                <label for="">Task Brief</label>
                                <textarea name="description" placeholder="Add a description for your Task" required id="description"></textarea>
                            </div>

                            {{-- <span class="info-text">Add a description for your Task</span> --}}
                        </div>
                        <div class="row m-0">
                            <div class="col-xl-4">
                                <div class="input-wrap">


                                    <div class="form-group">
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <div> Active</div>
                                                <input type="checkbox" name="is_active" />
                                                <span></span>

                                            </label>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                            <div class="sic-btn">
                                <a class="btn create-task" id="uploadAsset">
                                    upload assets
                                </a>
                                <button class="btn download" id="save">
                                    save
                                </button>
                                <span class="btn link-asset" data-dismiss="modal" id="cancel"
                                    aria-label="Close">cancel</span>
                            </div>

                        </div>

                        <div class="img-upload-con d-none">
                            <div class="upload--col w-100">
                                <div class="drop-zone">
                                    <div class="drop-zone__prompt">

                                        <div class="drop-zone_color-txt">
                                            <span><img src="assets/images/Image.png" alt=""></span> <br />
                                            <span><img src="assets/images/fi_upload-cloud.svg" alt=""> Upload
                                                Image</span>
                                        </div>

                                        <div class="file-format">
                                            <p>Upload a cover image for your product.</p>
                                            <p>File Format <b> jpeg, png</b>. Recommened Size <b>600x600 (1:1)</b></p>
                                        </div>
                                    </div>
                                    <input type="file" name="myFile" class="drop-zone__input">
                                </div>

                                <!-- <button type="submit" class="primary-btn">Add</button> -->
                            </div>
                            {{-- <div class="additional-img">
                                <label for="">Additional Images</label>

                                <div class="upload--col">
                                    <div class="drop-zone">
                                        <div class="drop-zone__prompt">

                                            <div class="drop-zone_color-txt">
                                                <span><img src="assets/images/Image.png" alt=""></span> <br />
                                                <span><img src="assets/images/fi_upload-cloud.svg" alt=""> Upload
                                                    Image</span>
                                            </div>
                                        </div>
                                        <input type="file" name="myFile" class="drop-zone__input">
                                    </div>

                                    <!-- <button type="submit" class="primary-btn">Add</button> -->
                                </div>
                            </div> --}}
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
$(document).ready(function () {
    $('.btn.link-asset').on('click', function () {
        const url = $(this).data('url');
        const description = $(this).data('description');
        openLinkModal(url, description);
    });

    function openLinkModal(publicUrl,description) {
        // Update text for the asset link
        $('#assetLink').text(publicUrl);

        // Encode URL and description
        const encodedDescription = encodeURIComponent(description || "Check out this image!");
        const encodedUrl = encodeURIComponent(publicUrl);

        // Debug logs
        console.log("Public URL: ", publicUrl); // Check if URL is valid
        console.log("Encoded URL: ", encodedUrl); // Check the encoded URL

        // Set social media share links
        $('#linkedinShare').attr('href', `https://www.linkedin.com/shareArticle?mini=true&url=${encodedUrl}&summary=${encodedDescription}&title=${encodedDescription}`);
        $('#facebookShare').attr('href', `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}&quote=${encodedDescription}`);
        $('#twitterShare').attr('href', `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedDescription}`);
        $('#redditShare').attr('href', `https://www.reddit.com/submit?url=${encodeURIComponent(publicUrl)}`);

        // Show the modal
        $('#linkAssetModal').modal('show');
    }
});

    
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.download');

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const imageUrl = button.getAttribute('data-url');

                    // Create a temporary <a> element
                    const link = document.createElement('a');
                    link.href = imageUrl;
                    link.setAttribute('download', '');

                    // Append to the document and trigger the download
                    document.body.appendChild(link);
                    link.click();

                    // Cleanup the temporary <a> element
                    document.body.removeChild(link);
                });
            });
        });

function openLinkModal(publicUrl,description) {
    console.log(publicUrl);
    console.log(description);
    
    $('#assetLink').text(publicUrl);
    
    var encodedDescription = encodeURIComponent(description || "Check out this image!");
    var encodedUrl = encodeURIComponent(publicUrl);

    console.log("Public URL: ", publicUrl); // Check if URL is valid
    console.log("Encoded URL: ", encodedUrl); // Check the encoded URL
    
    $('#linkedinShare').attr('href', `https://www.linkedin.com/shareArticle?mini=true&url=${encodedUrl}&summary=${encodedDescription}&title=${encodedDescription}`);
    $('#facebookShare').attr('href', `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}&quote=${encodedDescription}`);
    $('#twitterShare').attr('href', `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedDescription}`);
    $('#redditShare').attr('href',`https://www.reddit.com/submit?url=${encodeURIComponent(publicUrl)}`);

    $('#linkAssetModal').modal('show');
}
</script>
@endsection
