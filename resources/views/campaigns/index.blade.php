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
            <div class="campaingn-table pb-3 common-table">

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
                <!-- campaigns-contents -->
                <div class="table-wrapper">
                    <table id="datatable">
                        <thead>
                            <tr>

                                <th>
                                    <span>S.No</span>
                                </th>
                                <th class="name">
                                    <span>Name</span>
                                </th>
                                <th class="description">
                                    <span>Description</span>
                                </th>
                                <th>
                                    <span>Due Date</span>
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
                                    <th class="active">
                                        <span>Action</span>
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campaigns as $campaign)
                                <tr>

                                    <td>
                                        <span>{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="name">
                                        <span>{{ $campaign->name }}</span>
                                    </td>
                                    <td class="description">
                                        <span>{!! $campaign->description !!}</span>
                                    </td>
                                    <td>
                                        <span>{{ $campaign->due_date ? \Carbon\Carbon::parse($campaign->due_date)->format('Y-m-d') : '' }}</span>
                                    </td>
                                    <td>
                                        <span>
                                            <p class="status {{ $campaign->is_active ? 'green' : 'red' }}">
                                                {{ $campaign->is_active ? 'Active' : 'Inactive' }}</p>
                                        </span>
                                    </td>
                                    @if ($showButton || $editButton || $deleteButton)
                                        <td class="active action-btn-icons">
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
                                                    onclick="editCampaign({{ $campaign }},'{{ $image_url }}')">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                            @endif

                                            @if ($deleteButton)
                                                <form id="Model-Form"
                                                    action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn comment"><i
                                                            class="bx bx-trash"></i></button>
                                                </form>
                                            @endif
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

    <!-- Modal Structure -->
    <div class="modal fade createTask-modal" id="createcampaign" tabindex="-1" aria-labelledby="ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Campaign</h1>
                    <p class="status green active_header_block" id="active_header_block" style="display: none;">Active</p>
                    <p class="status red inactive_header_block" id="inactive_header_block" style="display: none;">Inactive
                    </p>
                    <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal"
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
                        </div>

                        <div class="row m-0">
                            <!-- Client Dropdown -->
                            @if ($role_level < 3)
                                <div class="col-xl-4 mb-3">
                                    <label for="client">Select Client</label>
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
                                    <label for="clientGroup">Select Client Group</label>
                                    <select name="clientGroup" id="clientGroup" class="form-control" disabled>
                                        <option value="" disabled selected>Select Client Group</option>
                                    </select>

                                </div>
                            @else
                                <div class="col-xl-4 mb-3">
                                    <label for="clientGroup">Select Client Group</label>
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

                                <div class="multiselect_dropdown">
                                    <label for="clientGroup">Select Partner</label>

                                    <select name="related_partner[]" class="selectpicker" id="related_partner"
                                        class="selectpicker" multiple aria-label="size 1 select example " multiple
                                        data-selected-text-format="count > 5" data-live-search="true">
                                        <option value="" disabled>Select Related Partners</option>

                                    </select>
                                </div>

                            </div>
                        </div>

                        {{-- <li><a role="option" class="dropdown-item" id="bs-select-1-1" tabindex="0" aria-selected="false" aria-setsize="3" aria-posinset="1"><span class=" bs-ok-default check-mark"></span><span class="text">New Partner 1</span></a></li>
                        <li><a role="option" class="dropdown-item" id="bs-select-1-2" tabindex="0" aria-selected="false" aria-setsize="3" aria-posinset="2"><span class=" bs-ok-default check-mark"></span><span class="text">New Partner 1</span></a></li>
                        <li><a role="option" class="dropdown-item" id="bs-select-1-3" tabindex="0" aria-selected="false" aria-setsize="3" aria-posinset="3"><span class=" bs-ok-default check-mark"></span><span class="text">New Partner 1</span></a></li> --}}

                        <div class="row m-0">
                            <div class="col-md-12 mb-3">
                                <label for="description">Campaign Brief</label>
                                <textarea name="description" id="editor" class="form-control" rows="3"
                                    placeholder="Add a description for your campaign"></textarea>
                            </div>
                        </div>
                        <div class="row m-0 ">
                            <div class="col-3" id="active_block" style="display: none;">
                                <div class="profile-con add-partner-status">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="profile-label">Status:</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <div>
                                                <input type="checkbox" id="active" name="active" value="active">
                                                <label for="active">Active</label><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12" id="existingImageDiv" style="display: none;">
                                    <div class="profile-con add-partner-status">
                                        <div class="row">
                                            <div class="col-sm-4">

                                                <label for="cover_image">Existing Cover
                                                    Image:</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div>
                                                    <img id="existingImage" src="" alt="Cover Image"
                                                        class="img-thumbnail mb-3 w-25" style="">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="sic-action-btns d-flex justify-content-lg-end  flex-wrap">
                                <div class="sic-btn">

                                    <button type="button" class="btn download" id="uploadAsset">Upload Assets</button>
                                    <button type="submit" class="btn create-task">Save</button>
                                    <button type="button" class="btn link-asset" id="cancel"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>

                            <div class="img-upload-con d-none">
                                {{-- <div class="upload--col">
                                    <div class="drop-zone">
                                        <div class="drop-zone__prompt">
                                            <div class="drop-zone_color-txt">
                                                <span><img src="assets/images/Image.png" alt=""></span> <br />
                                                <span><img src="assets/images/fi_upload-cloud.svg" alt=""> Upload
                                                    Image</span>
                                            </div>
                                            <div class="file-format">
                                                <p>Upload images for your product.</p>
                                                <p>File Format: <b>jpeg, png</b>. Recommended Size: <b>600x600 (1:1)</b></p>
                                            </div>
                                        </div>
                                        <input type="file" id="cover_image" name="cover_image" class="drop-zone__input" accept="image/*">
                                    </div>
                                </div>
                                
                                <!-- Container to display the list of added images -->
                                <div id="file-list-container" class="mt-3">
                                    <ul id="file-list"></ul>
                                </div> --}}
                                <div class="sic-action-btns justify-content-center flex-wrap">
                                    <div class="addmore"><button type="button" id="add-more-btn"
                                            class="btn download">Add More</button>
                                    </div>
                                </div>

                                <div id="additional-images-container" class="additional-img my-3">
                                    {{-- <label>Additional Images</label> --}}
                                    <div class="multiple-image" id="multiple-image" style="display: flex;">
                                        <div class="upload--col">
                                            <div class="drop-zone">
                                                <div class="drop-zone__prompt">
                                                    <div class="drop-zone_color-txt">
                                                        <span><img src="assets/images/Image.png" alt=""></span>
                                                        <br />
                                                        <span><img src="assets/images/fi_upload-cloud.svg" alt="">
                                                            Upload Image</span>
                                                    </div>
                                                </div>
                                                <input type="file" name="additional_images[]"
                                                    class="drop-zone__input">
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

function editCampaign(campaign, imgUrl) {
    // Change form action and method for updating
    const $form = $('#campaignForm');
    $form.attr('action', `/campaigns/${campaign.id}`);
    $('#campaignMethod').val('PUT');

    // Populate form fields with campaign data
    $('#campaign_name').val(campaign.name);
    
    // Extract date portion from due_date (YYYY-MM-DD)
    const formattedDate = campaign.due_date.split(' ')[0];
    $('#datepicker').val(formattedDate);

    $('#related_partner').val(campaign.related_partner);
    // $('.description').val(campaign.description);

    // Handle active checkbox
    const $activeCheckbox = $('#active');
    $activeCheckbox.prop('checked', campaign.is_active === 1);

    // Show active checkbox visibility blocks
    $('#active_block').show();
    $('#active_header_block').show();

    console.log(campaign);

    // Handle client dropdown selection
    const $clientDropdown = $('#client');
    $clientDropdown.val(campaign.client_id);

    // Handle group dropdown selection
    const $groupDropdown = $('#clientGroup');
    $groupDropdown.val(campaign.Client_group_id);

    // If the client ID or group ID is not in the dropdown list, add it dynamically
    if (!$clientDropdown.find(`option[value="${campaign.client_id}"]`).length) {
        $clientDropdown.append(
            $('<option>', {
                value: campaign.client_id,
                text: `Client ${campaign.client_id}`, // Customize based on your data
            })
        ).val(campaign.client_id);
    }

    if (!$groupDropdown.find(`option[value="${campaign.Client_group_id}"]`).length) {
        $groupDropdown.append(
            $('<option>', {
                value: campaign.Client_group_id,
                text: `Group ${campaign.Client_group_id}`, // Customize based on your data
            })
        ).val(campaign.Client_group_id);
    }

    // Toggle active/inactive header blocks
    if (campaign.is_active === 1) {
        $('#active_header_block').show();
        $('#inactive_header_block').hide();
    } else {
        $('#inactive_header_block').show();
        $('#active_header_block').hide();
    }

    // Display existing cover image if available
    const $existingImageDiv = $('#existingImageDiv');
    const $existingImage = $('#existingImage');
    if (imgUrl) {
        $existingImage.attr('src', imgUrl); // Set the image source to the URL passed from the backend
        $existingImageDiv.show();
    } else {
        $existingImageDiv.hide();
    }

    if (window.editor) {
        window.editor.setData(campaign.description);  // Set data to CKEditor
    }


    // Display the modal
    $('#createcampaign').modal('show');
}
</script>
@endsection
