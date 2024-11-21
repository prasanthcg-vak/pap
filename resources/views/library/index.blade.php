@extends('layouts.app')

@section('content')
<div class="CM-main-content">
    <div class="container-fluid p-0">
        <!-- Table -->
        <div class="task campaingn-table pb-3 common-table">
            <!-- campaigns-contents -->
            <div class="col-lg-12 task campaigns-contents">
                <div class="campaigns-title">
                    <h3>LIBRARY</h3>
                </div>
                <form class="d-flex align-items-center gap-4">
                    <div class="layout-view d-inline-flex">
                        <div class=" layout-btn list active">
                            <i class="box-icon bx bx-list-ul"></i>
                        </div>
                        <div class="layout-btn grid">
                            <i class='box-icon bx bxs-grid-alt'></i>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="list-view">
                    <thead>
                        <tr>
                            <th class="">
                                <span>thumbnail</span>
                            </th>
                            <th>
                                <span>Campaign</span>
                            </th>
                            <th>
                                <span>file name</span>
                            </th>
                            <th class="">
                                <span>Dimensions</span>
                            </th>
                            <th class="">
                                <span>category</span>
                            </th>
                            <th class="status">
                                <span>status</span>
                            </th>
                            <th class="">
                                <span>action</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $index => $asset)
                            <tr>
                                <td class="library-img">
                                    <span>
                                    @php
                                        $thumbnail = match($asset['image_type']) {
                                            'image' => $asset['thumbnail'],
                                            'video' => asset('assets/images/video.png'),
                                            default => asset('assets/images/document.png'),
                                        };
                                    @endphp

                                    <img src="{{ $thumbnail }}" class="img-fluid" style="max-height: 200px;" alt="{{ $asset['image_name'] }}">
                                    {{-- <img class="img-fluid"  src="{{ $asset['thumbnail'] }}" alt=""> --}}
                                </span>
                                </td>
                                <td class="library-camp-title">
                                    <span>{{ $asset['campaign_name'] }}</span>
                                </td>
                                <td class="library-file-name">
                                    <span>{{ $asset['image_name'] }}</span>
                                </td>
                                <td class="library-file-size">
                                    <span>{{ $asset['dimensions'] }}px
                                    </span>
                                </td>
                                <td class="library-file-category">
                                    <span>{{ $asset['category'] }}
                                    </span>
                                </td>
                                <td class="library-status">
                                    {!! $asset['status'] ? '<span class="status green">Active</span>' : '<span class="status red">Inactive</span>' !!}
                                </td>
                                <td class="library-action">
                                    <div class="action-btn-icons">
                                        {{-- <button 
                                            class="btn download-btn" 
                                            data-url="{{ $asset['thumbnail'] }}">
                                            <i class='bx bx-download'></i>
                                        </button> --}}
                                        <button 
                                            class="btn download-btn" 
                                            data-url="{{ $asset['thumbnail'] }}">
                                            <i class='bx bx-download'></i>
                                        </button>
                                        <button class="btn btn-link new-link" onclick="openLinkModal('{{ $asset['thumbnail'] }}','{{ $asset['description'] }}')"><i class='bx bx-link-external'></i></button>
                                        
                                        {{-- <form action="{{ route('images.delete') }}" method="POST" style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this asset?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="path" value="{{ $asset['image_path'] }}">
                                            <button type="submit" class="btn delete"><i class='bx bx-trash'></i></button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
                            {{--<input type="text" value="" id="myInput">
                            <button onclick="copyToClipboard()">Copy text</button>--}}

                            <div class="web-link-col">
                                <div class="row m-0 align-items-center">
                                    <div class="col-9">
                                        <div class="check-list">
                                            <span>
                                                Web Link : <span id="assetLink" style="color:#EB8205"></span>
                                            </span>
                                        </div>
                                    </div>
                                    {{--<div class="col-3 text-end">
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
                                    </div>--}}
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

@endsection
@section('script')
<script>
$(document).ready(function() {
    $('#tasksTable').DataTable({
        responsive: true,
        pageLength: 10,
        columnDefs: [
            { 
                searchable: false, 
                orderable: false, 
                targets: 0 // First column for row numbers
            }
        ],
        order: [[1, 'asc']], // Initial sort by the second column (Name)
        drawCallback: function(settings) {
            var api = this.api();
            api.column(0, { order: 'applied' }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1; // Dynamically update row numbers
            });
        }
    });

    $(".layout-btn").click(function() {
        var targetTable = $(".common-table table");

            // Remove 'active' class from all buttons and add it to the clicked button
        $(".layout-btn").removeClass("active");
        $(this).addClass("active");
            
        // Check if the clicked button has the 'list' class
        if ($(this).hasClass("list")) {
            $(this)
            targetTable.removeClass("grid-view").addClass("list-view");
        } 
        // Otherwise, check if it has the 'grid' class
        else if ($(this).hasClass("grid")) {
            targetTable.removeClass("list-view").addClass("grid-view");
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.download-btn');

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

function copyToClipboard() {
    var copyText = $('#myInput').val();
    navigator.clipboard.writeText(copyText);
    alert("Copied the text: " + copyText);
}

</script>
@endsection
