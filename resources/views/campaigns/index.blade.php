@extends('layouts.app')

@section('content')
    <style>
        /* Style for image thumbnails */
        .preview-image {
            width: 100px;
            height: 100px;
            margin-right: 10px;
        }

        /* Style for the delete button */
        .delete-btn {
            margin-left: 10px;
        }
    </style>
    <!-- Table -->
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!-- Table -->
            <div class=" pb-3">
                <!-- campaigns-contents -->
                <div class="col-lg-12 task campaigns-contents">
                    <div class="campaigns-title">
                        <h3>CAMPAIGNS</h3>
                    </div>
                    @if (Auth::user()->hasRolePermission('campaigns.create'))
                        <a href="#" class="create-task-btn" id="createButton">Create
                            Campaign</a>
                    @endif
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <!-- campaigns-contents -->
                <div class="table-wrapper">
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>

                                <th class="">
                                    <span>Name</span>
                                </th>
                                <th class="">
                                    <span>Description</span>
                                </th>
                                {{-- @php
                                    // dd(Auth::user()->roles->first()->role_level);
                                @endphp --}}
                                @if (Auth::user()->roles->first()->role_level != 5)
                                    <th>
                                        <span>Client</span>
                                    </th>
                                @endif

                                <th>
                                    <span>Client Group</span>
                                </th>
                                <th>
                                    <span>Due Date</span>
                                </th>
                                <th>
                                    <span>Tasks</span>
                                </th>
                                <th>
                                    <span>Assets</span>
                                </th>
                                <th>
                                    <span>Status</span>
                                </th>
                                @php
                                    $showButton = Auth::user()->hasRolePermission('campaigns.show');
                                    $editButton = Auth::user()->hasRolePermission('campaigns.edit');
                                    $deleteButton = Auth::user()->hasRolePermission('campaigns.destroy');
                                @endphp
                                @if ($showButton || $editButton || $deleteButton)
                                    <th class="">
                                        <span>Action</span>
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campaigns as $campaign)
                                <tr>


                                    <td class="">
                                        <span>{{ $campaign->name }}</span>
                                    </td>

                                    <td class="">
                                        @php
                                            $words = explode(' ', strip_tags($campaign->description)); // Strip HTML tags and split into words
                                            $truncated = implode(' ', array_slice($words, 0, 15)); // Get the first 15 words
                                        @endphp
                                        <span class="truncated-text">
                                            {!! $truncated !!}{{ count($words) > 15 ? '...' : '' }}
                                        </span>
                                        @if (count($words) > 15)
                                            <span class="read-more"
                                                style="color: orange; cursor: pointer; text-decoration: underline;"> Read
                                                more</span>
                                            <span class="full-text" style="display: none;">
                                                {!! $campaign->description !!}
                                                <span class="read-less"
                                                    style="color: orange; cursor: pointer; text-decoration: underline;">
                                                    Read
                                                    less</span>
                                            </span>
                                        @endif
                                    </td>
                                    @if (Auth::user()->roles->first()->role_level != 5)
                                        <td>
                                            {{ $campaign->client ? $campaign->client->name : '-' }} </td>
                                    @endif
                                    <td>
                                        {{ $campaign->group ? $campaign->group->name : '-' }} </td>
                                    <td>
                                        <span>{{ $campaign->due_date ? \Carbon\Carbon::parse($campaign->due_date)->format('Y-m-d') : '' }}</span>
                                    </td>
                                    <td>

                                        @if ($campaign->tasks->isNotEmpty())
                                            <a href="{{ route('campaigns.tasks', ['id' => $campaign->id]) }}"
                                                class="">
                                                {{ $campaign->tasks->count() }}
                                            </a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    {{-- Asset count column --}}
                                    <td>
                                        @if ($campaign->images->isNotEmpty())
                                            <a href="{{ route('campaigns.assetsList', ['id' => $campaign->id]) }} "
                                                class="">
                                                {{ $campaign->images->count() }}
                                            </a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        <span>
                                            <p
                                                class="status {{ $campaign->status && $campaign->status->name === 'ACTIVE' ? 'green' : 'red' }}">
                                                {{ $campaign->status ? $campaign->status->name : 'Unknown' }}</p>
                                        </span>
                                    </td>
                                    @if ($showButton || $editButton || $deleteButton)
                                        <td class=" ">
                                            <div class="action-btn-icons">
                                                <!-- Show <td> only if any permission is true -->
                                                <!-- Trigger modal with campaign data -->
                                                @php
                                                    if (isset($campaign->image)) {
                                                        $image_url = Storage::disk('backblaze')->url(
                                                            $campaign->image->path,
                                                        ); // Add the image URL to the campaign object
                                                    } else {
                                                        $image_url = null;
                                                    }
                                                @endphp

                                                @if ($showButton)
                                                    <a href="{{ route('campaigns.show', ['id' => $campaign->id]) }}"
                                                        class="btn edit">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                @endif

                                                @if ($editButton)
                                                    <button type="button" class="btn search" data-bs-toggle="modal"
                                                        data-bs-target="#createcampaign"
                                                        onclick="editCampaign('{{ Crypt::encryptString($campaign->id) }}')">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                @endif

                                                @if ($deleteButton)
                                                    <form id="Model-Form"
                                                        action="{{ route('campaigns.destroy', $campaign->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn trash"><i
                                                                class="bx bx-trash"></i></button>
                                                    </form>
                                                @endif
                                            </div>

                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Table -->
        </div>
    </div>
    {{-- <div id="preloader">
        <div class="loader-main"></div>
    </div> --}}

    <!-- Modal Structure -->
    <div class="modal fade createTask-modal" id="createcampaign" tabindex="-1" aria-labelledby="ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="CampaignModalLabel">Create Campaign</h1>


                    <button type="button" class="btn-close" id="campaign-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div id="modalLoader" class="modal-loader" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="campaignForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="campaignMethod">
                        <div class="row m-0">
                            <div class="col-xl-4 mb-3">
                                <label for="">Campaign Name</label>
                                <input type="text" name="name" id="campaign_name" class="form-control"
                                    placeholder="Campaign Name" required>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <label for="">Due Date</label>
                                <input type="date" name="due_date" id="datepicker" class="form-control"
                                    placeholder="Date">
                            </div>
                            <div class="col-xl-4 mb-3 " id="select-status">
                                <label for="status">Status</label>
                                <select name="status_id" id="status_id" class="status form-control">
                                    @foreach ($status as $key => $stat)
                                        <option value="{{ $stat->id }}" id="{{ strtolower($stat->name) }}"
                                            {{ $key === 0 ? 'selected' : '' }}>
                                            {{ $stat->name }}
                                        </option>
                                    @endforeach


                                </select>
                            </div>
                        </div>

                        <div class="row m-0">
                            <!-- Client Dropdown -->
                            @if ($role_level < 3)
                                <div class="col-xl-4 mb-3">
                                    <label for="client" class="form-label">Select Client</label>
                                    <select name="client" id="client" class="form-control" required>
                                        <option value="" disabled selected>Select Client</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="client" value="{{ $client_id }}">
                            @endif
                            <!-- Client Group Dropdown -->
                            @if ($role_level < 3)
                                <div class="col-xl-4 mb-3">
                                    <label for="clientGroup" class="form-label">Select Client Group</label>
                                    <select name="clientGroup" id="clientGroup" class="form-control">
                                        <option value="" disabled selected>Select Client Group</option>
                                    </select>
                                </div>
                            @else
                                <div class="col-xl-4 mb-3">
                                    <label for="clientGroup" class="form-label">Select Client Group</label>
                                    <select name="clientGroup" id="clientGroup" class="form-control">
                                        <option value="" selected>Select Client Group</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Partners Multi-Select Dropdown -->
                            <div class="col-xl-4 mb-3">
                                <label for="related_partner" class="form-label">Select Partner</label>
                                <div class="multiselect_dropdown">
                                    <select name="related_partner[]" class="selectpicker" id="related_partner" multiple
                                        aria-label="size 1 select example" data-selected-text-format="count > 5"
                                        data-live-search="true">
                                        <option value="" disabled>Select Related Partners</option>
                                    </select>
                                </div>

                            </div>

                        </div>
                        <div class="row m-0">
                            <!-- Staff Multi-Select Dropdown -->
                            <div class="col-xl-4 mb-3">
                                <label for="related_partner" class="form-label">Select Staff</label>
                                <div class="multiselect_dropdown">
                                    <select name="staff[]" class="selectpicker" id="staff" multiple
                                        aria-label="size 1 select example" data-selected-text-format="count > 5"
                                        data-live-search="true">
                                        <option value="" disabled>Select Staff</option>
                                        @foreach ($staffs as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-md-12 mb-3">
                                <label for="description">Campaign Brief</label>
                                <textarea name="description" id="editor" class="form-control" rows="3"
                                    placeholder="Add a description for your campaign"></textarea>
                            </div>
                        </div>
                        <div class="row m-0 ">
                            {{-- <div class="col-3" id="active_block" style="display: none;">
                                <div class="profile-con add-partner-status">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="profile-label">Status:</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <div>
                                                <input type="checkbox" id="isactive" name="active" value="active">
                                                <label for="active">Active</label><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row row-cols-1 row-cols-md-3 g-4 d-none" id="existingImageDiv">
                            </div>
                            <div class="sic-action-btns d-flex justify-content-lg-end  flex-wrap mt-4">
                                <div class="sic-btn">
                                    <button type="button" class="btn download" id="uploadAsset">Upload Assets</button>
                                    <button type="submit" class="btn create-task">Save</button>
                                    <button type="button" class="btn cancel" id="campaign-cancel"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                            <div class="img-upload-con d-none">
                                <div class="sic-action-btns justify-content-center flex-wrap">
                                    <div class="addmore"><button type="button" id="add-more-btn"
                                            class="btn download">Add More</button>
                                    </div>


                                    <div style="font-size:10px;">
                                        <ol style="padding:1rem; color:var(--Gold-Drop)">
                                            <li>
                                                Please upload
                                                any assets required for this campaign.
                                            </li>
                                            <li>
                                                To upload multiple assets, click the Add
                                                More button and upload assets individually.
                                            </li>
                                            <li>Click Save once finished.</li>
                                            <li>Assets
                                                can be uploaded at a later date.</li>
                                        </ol>
                                    </div>
                                </div>

                                <div id="additional-images-container" class="additional-img my-3">
                                    <div class="multiple-image" id="multiple-image" style="display: flex;">
                                        <div class="upload--col">
                                            <div class="drop-zone">
                                                <div class="drop-zone__prompt">
                                                    <div class="drop-zone_color-txt">
                                                        <span><img src="assets/images/Image.png"
                                                                alt=""></span><br />
                                                        <span style="font-size:14px;"><img
                                                                src="assets/images/fi_upload-cloud.svg" alt="">
                                                            Upload Asset</span>
                                                        <span style="font-size:10px;">(JEPG, PNG, JPG, MP4, PDF,
                                                            JFIF).</span>
                                                    </div>
                                                </div>
                                                <input type="file" name="additional_images[]" class="drop-zone__input"
                                                    onchange="handleFileChange(this)">
                                            </div>
                                            <!-- Thumbnail Upload for video and PDF files -->
                                            <div class="thumbnail-upload" style="display: none;">
                                                <label for="thumbnail">Upload Thumbnail (for Video/PDF):</label>
                                                <div class="drop-zone">
                                                    <div class="drop-zone__prompt">
                                                        <div class="drop-zone_color-txt">
                                                            <span><img src="assets/images/Image.png"
                                                                    alt=""></span><br />
                                                            <span style="font-size:14px;"><img
                                                                    src="assets/images/fi_upload-cloud.svg"
                                                                    alt="">
                                                                Upload Asset</span>
                                                            <span style="font-size:10px;">(JEPG, PNG, JPG).</span>
                                                        </div>
                                                    </div>
                                                    <input type="file" name="thumbnail[]" class="drop-zone__input"
                                                        onchange="handleThumbnailFileChange(this)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable().destroy();
            $('#datatable').DataTable({
                responsive: true,
                pageLength: 10,
                columnDefs: [{
                    searchable: false,
                    orderable: false,
                    targets: 0
                }],
                order: [
                    [1, 'asc']
                ], // Initial sort by name
                // drawCallback: function(settings) {
                //     var api = this.api();
                //     api.column(0, {
                //         order: 'applied'
                //     }).nodes().each(function(cell, i) {
                //         cell.innerHTML = i + 1; // Number rows dynamically
                //     });
                // }
            });

            // Add form validation on submission
            $('#campaignForm').on('submit', function(e) {
                let isValid = true;

                $('input[name="additional_images[]"]').each(function() {
                    const file = this.files[0];
                    const parentDiv = $(this).closest('.upload--col');
                    const thumbnailInput = parentDiv.find('input[name="thumbnail[]"]');

                    if (file) {
                        const fileType = file.type;
                        const isVideoOrPdf = fileType.includes('video') || fileType ===
                            'application/pdf';

                        if (isVideoOrPdf && (!thumbnailInput.length || !thumbnailInput[0].files
                                .length)) {
                            isValid = false;
                            alert(`A thumbnail is required for the file: ${file.name}`);
                        }
                    }
                });

                if (!isValid) {
                    e.preventDefault(); // Prevent form submission
                }
            });
        });

        function handleFileChange(inputElement) {
            const file = inputElement.files[0];
            const fileType = file.type; // Get the MIME type of the file
            const uploadColDiv = inputElement.closest('.upload--col'); // Find the corresponding parent
            let thumbnailDiv = uploadColDiv.querySelector('.thumbnail-upload'); // Look for an existing thumbnail div

            console.log(fileType);

            // Show thumbnail input if the file is a video or PDF
            if (fileType.includes('video') || fileType === 'application/pdf') {
                if (!thumbnailDiv) {
                    // If thumbnail div doesn't exist, create and append it
                    const thumbnailUploadSection = `
            <div class="thumbnail-upload" >
                <label for="thumbnail">Upload Thumbnail (for Video/PDF):</label>
                <div class="drop-zone">
                    <div class="drop-zone__prompt">
                        <div class="drop-zone_color-txt">
                            <span><img src="assets/images/Image.png" alt=""></span><br />
                            <span style="font-size:14px;"><img src="assets/images/fi_upload-cloud.svg" alt="">
                                Upload Asset</span>
                            <span style="font-size:10px;">(JPEG, PNG, JPG).</span>
                        </div>
                    </div>
                    <input type="file" name="thumbnail[]" class="drop-zone__input" onchange="handleThumbnailFileChange(this)">
                </div>
            </div>`;

                    $(uploadColDiv).append(thumbnailUploadSection); // Append to the corresponding upload--col
                    thumbnailDiv = uploadColDiv.querySelector('.thumbnail-upload .drop-zone'); // Update reference
                    if (thumbnailDiv) {
                        initializeDropZone(thumbnailDiv);
                    }
                }

                // thumbnailDiv.style.display = 'block'; // Make sure the thumbnail div is visible
            } else {
                // If the file is not a video or PDF, remove the thumbnail div if it exists
                if (thumbnailDiv) {
                    thumbnailDiv.remove();
                }
            }
        }

        function handleThumbnailFileChange(inputElement) {
            const file = inputElement.files[0];
            const fileType = file.type; // Get the MIME type of the file

            console.log(fileType);

            // Show thumbnail input if the file is a video or PDF
            if (fileType.includes('video') || fileType === 'application/pdf') {
                alert('Please upload only image files (e.g., .jpg, .png, .jpeg) as a thumbnail.');
                inputElement.value = '';
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function editCampaign(encryptedCampaignId) {
            $('body').addClass('loading'); // Show loader
            $('#modalLoader').show();
            $('#CampaignModalLabel').text('Edit Campaign'); // Set modal title to "Edit Campaign"

            $.ajax({
                url: `/campaigns/edit/${encryptedCampaignId}`,
                type: 'GET',
                success: function(response) {
                    updateModalData(response);
                    $('#modalLoader').hide();

                },
                error: function(xhr) {
                    $('body').removeClass('loading');
                    console.error(xhr.responseText);
                    alert('An error occurred. Please try again.');
                    $('#modalLoader').hide();

                }
            });
        }

        function updateModalData(campaignData) {
            const {
                campaign,
                clientGroups,
                clientPartners,
                images,
                staff, // Staff data added
            } = campaignData;
            const partners = campaign.partner;

            // Update form action and method
            const $form = $('#campaignForm');
            $form.attr('action', `/campaigns/${campaign.id}`);
            $('#campaignMethod').val('PUT');

            // Populate form fields
            $('#campaign_name').val(campaign.name);
            $('#datepicker').val(campaign.due_date.split(' ')[0]); // Extract date (YYYY-MM-DD)
            if (campaign.status_id == 3) {
                document.getElementById('archived').style.display = 'block';
                document.getElementById('cancelled').style.display = 'block';
                document.getElementById('active').style.display = 'none';
                document.getElementById('inactive').style.display = 'none';
                document.getElementById('completed').style.display = 'none';

            }
            if (campaign.status_id == 4) {
                document.getElementById('archived').style.display = 'block';
                document.getElementById('completed').style.display = 'block';
                document.getElementById('active').style.display = 'none';
                document.getElementById('inactive').style.display = 'none';
                document.getElementById('cancelled').style.display = 'none';

            }
            if (campaign.status_id == 5) {
                document.getElementById('archived').style.display = 'block';
                document.getElementById('completed').style.display = 'none';
                document.getElementById('active').style.display = 'none';
                document.getElementById('inactive').style.display = 'none';
                document.getElementById('cancelled').style.display = 'none';

            }
            // Handle active checkbox
            const isActive = campaign.is_active === 1;
            $('#active').prop('checked', isActive);
            $('#active_block, #active_header_block').toggle(isActive);
            $('#inactive_header_block').toggle(!isActive);

            // Set client dropdown value
            $('#client').val(campaign.client_id);
            $('#status_id').val(campaign.status_id);


            // Populate client group dropdown
            const $groupDropdown = $('#clientGroup').empty().append(
                $('<option>', {
                    value: '',
                    text: 'Select Client Group',
                    disabled: true
                })
            );
            clientGroups.forEach(group => {
                $groupDropdown.append(
                    $('<option>', {
                        value: group.id,
                        text: group.name
                    })
                );
            });
            $groupDropdown.val(campaign.Client_group_id);

            // Populate related partner dropdown
            const $partnerDropdown = $('#related_partner').empty().prop('disabled', false);
            if (Array.isArray(clientPartners) && clientPartners.length > 0) {
                clientPartners.forEach(partner => {
                    $partnerDropdown.append(
                        `<option value="${partner.partner.id}">${partner.partner.name}</option>`
                    );
                });
            } else {
                $partnerDropdown.append(`<option value="">No Partners</option>`);
            }
            $('.selectpicker').selectpicker('refresh'); // Refresh dropdown UI

            // Set selected values for partners
            if (Array.isArray(partners) && partners.length > 0) {
                const selectedPartnerIds = partners.map(partner => partner.partner_id);
                $partnerDropdown.val(selectedPartnerIds);
            }
            $('.selectpicker').selectpicker('refresh');

            // ========================== Add Staff Selection ========================== //
            const $staffDropdown = $('#staff'); // Clear staff dropdown


            // Set selected staff
            if (Array.isArray(campaign.staff) && campaign.staff.length > 0) {
                const selectedStaffIds = campaign.staff.map(st => st.staff_id);
                $staffDropdown.val(selectedStaffIds);
            }
            $('.selectpicker').selectpicker('refresh');

            // Set campaign description in CKEditor
            if (window.editor) {
                window.editor.setData(campaign.description);
            }

            // Handle existing images display
            const bucketUrl = "https://cm-pap01.s3.us-east-005.backblazeb2.com";
            const $existingImageDiv = $("#existingImageDiv").empty(); // Clear existing images

            if (Array.isArray(images) && images.length > 0) {
                images.forEach(asset => {
                    const thumbnailUrl = asset.thumbnail ? `${asset.thumbnail}` : `${asset.image}`;

                    $existingImageDiv.append(`
                <div class="col-md-3 col-image">
                    <div class="card existing-image-card">
                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-asset existing-remove-btn" data-id="${asset.id}">X</button>
                        <div class="drop-zone__thumb" data-label="${asset.file_name}" style="background-image: url(${thumbnailUrl});">
                        </div>
                    </div>
                </div>
            `);
                });
            }
            $existingImageDiv.toggleClass("d-none", campaign.images.length === 0); // Hide if no images

            // Display the modal and remove loading indicator
            // Check if status_id is 3
            // Check if status_id is 3
            // Check if status_id is 3
            if (campaign.status_id == 3 || campaign.status_id == 4 || campaign.status_id == 5) {
                $('#campaignForm :input').not('#status_id, .create-task, #campaignMethod,[name="_token"],#isactive').prop(
                    'disabled',
                    true); // Disable all except status_id & Save button

                $('.selectpicker').selectpicker('refresh'); // Refresh dropdowns
                if (window.editor) {
                    window.editor.disableReadOnlyMode('readonlyMode');
                }

            } else {
                $('#campaignForm :input').prop('disabled', false); // Enable all fields
                $('.selectpicker').selectpicker('refresh');
                document.getElementById('archived').style.display = 'none';
            }

            $('#createcampaign').modal('show');
            $('body').removeClass('loading');
        }

        $(document).on("click", ".existing-remove-btn", function(e) {
            e.preventDefault();
            const $button = $(this);
            const $assetCard = $button.closest(".col-image");
            const assetId = $button.data("id");
            if (!assetId) {
                console.error("Asset ID is missing.");
                alert("Invalid asset. Unable to remove.");
                return;
            }
            if (confirm("Are you sure you want to remove this asset?")) {
                $('#modalLoader').show();
                $.ajax({
                    url: `/campaigns/assets/${assetId}`,
                    type: "DELETE",
                    contentType: "application/json",
                    success: function(response) {
                        $('#modalLoader').hide();
                        if (response.success) {
                            $assetCard.remove();
                            alert("Asset removed successfully!");
                        } else {
                            console.warn("Failed to remove asset. Response:", response);
                            alert("Failed to remove asset. Please try again.");
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#modalLoader').hide();
                        console.error("Error occurred while removing asset:", {
                            status: xhr.status,
                            error: error,
                            response: xhr.responseText,
                        });
                        alert("An error occurred while removing the asset. Please try again.");
                    },
                });
            }
        });
    </script>
@endsection
