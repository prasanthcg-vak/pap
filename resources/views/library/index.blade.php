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
                            <div class="layout-btn list active" aria-label="Switch to list view">
                                <i class="box-icon bx bx-list-ul"></i>
                            </div>
                            <div class="layout-btn grid" aria-label="Switch to grid view">
                                <i class='box-icon bx bxs-grid-alt'></i>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-wrapper">
                    <table class="list-view card-grid-contents">
                        <thead>
                            <tr>
                                <th><span>Thumbnail</span></th>
                                <th><span>Campaign</span></th>
                                <th><span>File Name</span></th>
                                <th><span>Dimensions</span></th>
                                <th><span>Category</span></th>
                                <th><span>Client</span></th>
                                <th><span>Client Group</span></th>
                                <th class="status"><span>Status</span></th>
                                <th><span>Action</span></th>
                            </tr>
                        </thead>
                        <tbody class="card-grid-items">
                            @foreach ($assets as $index => $asset)
                                <tr>
                                    <td class="library-img">
                                        @php
                                            $thumbnail = match ($asset['image_type']) {
                                                'image' => $asset['image'],
                                                'video' => $asset['thumbnail'],
                                                default => $asset['thumbnail'],
                                            };
                                        @endphp
                                        <img src="{{ $thumbnail }}" class="img-fluid" style="max-height: 200px;" alt="{{ $asset['image_name'] }}">
                                    </td>
                                    <td class="library-camp-title">
                                        <span>{{ $asset['campaign_name'] }}</span>
                                    </td>
                                    <td class="library-file-name">
                                        <span>{{ $asset['image_name'] }}</span>
                                    </td>
                                    <td class="library-file-size">
                                        <span>{{ $asset['dimensions'] }}px</span>
                                    </td>
                                    <td class="library-file-category">
                                        <span>{{ $asset['category'] }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $asset['client']->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $asset['group']->name ?? '-' }}</span>
                                    </td>
                                    <td class="library-status">
                                        {!! $asset['status'] ? '<span class="status green">Active</span>' : '<span class="status red">Inactive</span>' !!}
                                    </td>
                                    <td class="library-action">
                                        <div class="action-btn-icons">
                                            <button class="btn download-btn" data-url="{{ $asset['image'] }}" aria-label="Download {{ $asset['image_name'] }}">
                                                <i class='bx bx-download'></i>
                                            </button>
                                            <button class="btn btn-link new-link" onclick="createPost('{{ $asset['image_id'] }}', '{{ $asset['id'] }}')" aria-label="Create post for {{ $asset['image_name'] }}">
                                                <i class='bx bx-link-external'></i>
                                            </button>
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
    <div class="modal fade linkAsset-modal" id="linkAssetModal" tabindex="-1" aria-labelledby="linkAssetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="linkAssetModalLabel">Link Asset</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0">
                        <div class="col-md-12 mb-4">
                            <h4 class="bold-labels">Public Access</h4>
                            <div class="web-link-col">
                                <div class="row m-0 align-items-center">
                                    <div class="col-9">
                                        <div class="check-list">
                                            <span>Web Link: <span id="assetLink" style="color:#EB8205"></span></span>
                                        </div>
                                    </div>
                                    <div class="col-3 text-end">
                                        <button class=" btn copy-web-link p-0" onclick="copyToClipboard(document.getElementById('assetLink').textContent)">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10 col-xl-6 mb-4">
                            <h4 class="bold-labels">Social Media Access</h4>
                            <p>Click on the icons below and follow the instructions to post your banner to your social media account.</p>
                            <div class="social-links">
                                <ul>
                                    <li><a href="#" id="linkedinShare" target="_blank" aria-label="Share on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></li>
                                    <li><a href="#" id="facebookShare" target="_blank" aria-label="Share on Facebook"><i class="fa-brands fa-facebook"></i></a></li>
                                    <li><a href="#" id="twitterShare" target="_blank" aria-label="Share on Twitter"><i class="fa-brands fa-x-twitter"></i></a></li>
                                    <li><a href="#" id="redditShare" target="_blank" aria-label="Share on Reddit"><i class="fa-brands fa-reddit-alien"></i></a></li>
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
            });

            $(".layout-btn").click(function() {
                $(".layout-btn").removeClass("active");
                $(this).addClass("active");
                const targetTable = $(".common-table table");
                targetTable.toggleClass("grid-view", $(this).hasClass("grid"));
                targetTable.toggleClass("list-view", $(this).hasClass("list"));
            });

            $('.download-btn').click(function() {
                const imageUrl = $(this).data('url');
                const link = $('<a>').attr('href', imageUrl).attr('download', '').appendTo('body');
                link[0].click();
                link.remove();
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function createPost(imageId, taskId) {
            $('body').addClass('loading'); // Show loader
            $.ajax({
                url: '/posts/create',
                type: 'POST',
                data: {
                    image: imageId,
                    task_id: taskId,
                    post_type: 'task'
                },
                success: function(response) {
                    $('body').removeClass('loading');
                    // Assuming the response contains post details and share URLs
                    const { postUrl, encodedDescription, socialLinks } = response;

                    // Update modal content with social links
                    $('#assetLink').text(postUrl);
                    $('#linkedinShare').attr('href', socialLinks.linkedin);
                    $('#facebookShare').attr('href', socialLinks.facebook);
                    $('#twitterShare').attr('href', socialLinks.twitter);
                    $('#redditShare').attr('href', socialLinks.reddit);

                    $('#linkAssetModal').modal('show');
                },
                error: function(xhr) {
                    $('body').removeClass('loading');
                    console.error(xhr.responseText);
                    alert('An error occurred. Please try again.');
                }
            });
        }

        function copyToClipboard(publicUrl) {
            // Create a temporary input element
            const tempInput = document.createElement('input');
            tempInput.value = publicUrl;

            // Append the input to the modal instead of the body
            const modal = document.querySelector('.modal.show'); // Select the currently visible modal
            if (modal) {
                modal.appendChild(tempInput);
            } else {
                document.body.appendChild(tempInput); // Fallback to body if no modal is visible
            }

            // Select and copy the text
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');

            // Remove the temporary input element
            tempInput.remove();

            // Show a confirmation message
            alert('Public URL copied to clipboard!');
        }


    </script>
@endsection
