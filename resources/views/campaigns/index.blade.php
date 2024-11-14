@extends('layouts.app')

@section('content')
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

                    <a href="#" class="create-task-btn" data-bs-toggle="modal" data-bs-target="#createcampaign">Create
                        Campaign</a>

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
                    <table id="add-row">
                        <thead>
                            <tr>

                                <th class="slno">
                                    <span>S.No</span>
                                </th>
                                <th class="campaingn-title1">
                                    <span>Name</span>
                                </th>
                                <th class="description">
                                    <span>Description</span>
                                </th>
                                <th class="campaingn-title">
                                    <span>Due Date</span>
                                </th>
                                <th class="campaingn-title">
                                    <span>Status</span>
                                </th>
                                <th class="active">
                                    <span>Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campaigns as $campaign)
                                <tr>

                                    <td class="slno">
                                        <span>{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="campaingn-title1">
                                        <span>{{ $campaign->name }}</span>
                                    </td>
                                    <td class="description">
                                        <span>{{ $campaign->description }}</span>
                                    </td>
                                    <td class="campaingn-title">
                                        <span>{{ $campaign->due_date ? \Carbon\Carbon::parse($campaign->due_date)->format('Y-m-d') : '' }}</span>
                                    </td>
                                    <td class="campaingn-title">
                                        <span>
                                            <p class="status green">{{ $campaign->is_active ? 'Active' : 'Inactive' }}</p>
                                        </span>
                                    </td>
                                    <td class="active action-btn-icons">
                                        <!-- Trigger modal with campaign data -->
                                        @php
                                            // dd($campaign->image);
                                            if (isset($campaign->image)) {
                                                $image_url = Storage::disk('backblaze')->url($campaign->image->path); // Add the image URL to the campaign object
                                                // dd($image_url);
                                            } else {
                                                $image_url = null;
                                            }

                                        @endphp
                                        {{-- @if ($campaign->image_id != null) --}}
                                        <a href="{{ route('campaigns.show', ['id' => $campaign->id]) }}" class="btn edit">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        {{-- @endif --}}
                                        

                                        <button type="button" class="btn search" data-bs-toggle="modal"
                                            data-bs-target="#createcampaign"
                                            onclick="editCampaign({{ $campaign }},'{{ $image_url }}')"><i
                                                class="bx bx-edit"></i></button>
                                        <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn comment"><i class="bx bx-trash"></i></button>
                                        </form>

                                    </td>
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
    <div class="modal fade createTask-modal" id="createcampaign" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Campaign</h1>
                    <p class="status green active_header_block" id="active_header_block" style="display: none;">Active</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="campaignForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="campaignMethod">
                        <div class="row m-0">
                            <div class="col-xl-4 mb-3">
                                <input type="text" name="name" id="campaign_name" class="form-control"
                                    placeholder="Campaign Name" required>
                            </div>
                            <div class="col-xl-4 mb-3">
                                <input type="date" name="due_date" id="date" class="form-control"
                                    placeholder="Date">
                            </div>

                            <div class="col-xl-4 mb-3">
                                <select name="related_partner[]" id="related_partner" class="form-select" multiple>
                                    <option value="" disabled>Select Related Partners</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}">{{ $partner->partner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-md-12 mb-3">
                                <label for="description">Campaign Brief</label>
                                <textarea name="description" id="campaign_brief" class="form-control" rows="3"
                                    placeholder="Add a description for your campaign"></textarea>
                                <span class="info-text">Add a description for your Task</span>
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
                                                <input type="checkbox" id="html" name="fav_language"
                                                    value="HTML">
                                                <label for="html">Active</label><br>
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



                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn thumbs-up " id="cancel"  data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn search m-2" id="uploadAsset">Upload Assets</button>
                                <button type="submit" class="btn edit m-2">Save</button>
                            </div>

                            <div class="img-upload-con d-none">
                                <div class="upload--col">
                                    <div class="drop-zone">
                                        <div class="drop-zone__prompt">
                                            <div class="drop-zone_color-txt">
                                                <span><img src="assets/images/Image.png" alt=""></span> <br />
                                                <span><img src="assets/images/fi_upload-cloud.svg" alt=""> Upload
                                                    Cover
                                                    Image</span>
                                            </div>
                                            <div class="file-format">
                                                <p>Upload a cover image for your product.</p>
                                                <p>File Format: <b>jpeg, png</b>. Recommended Size: <b>600x600 (1:1)</b></p>
                                            </div>
                                        </div>
                                        <input type="file" name="cover_image" class="drop-zone__input">
                                    </div>
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
                                        <input type="file" name="additional_images[]" class="drop-zone__input"
                                            multiple>
                                    </div>
                                </div>
                            </div> --}}
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function editCampaign(campaign, imgUrl) {
            // Change form action and method for updating
            const form = document.getElementById('campaignForm');
            form.action = `/campaigns/${campaign.id}`;
            document.getElementById('campaignMethod').value = 'PUT';

            // Populate form fields with campaign data
            document.getElementById('campaign_name').value = campaign.name;

            // Extract date portion from due_date (YYYY-MM-DD)
            const formattedDate = campaign.due_date.split(' ')[0];
            document.getElementById('date').value = formattedDate;

            document.getElementById('related_partner').value = campaign.related_partner;
            document.getElementById('campaign_brief').value = campaign.description;

            // Handle active checkbox visibility
            document.getElementById('active_block').style.display = 'block';
            document.getElementById('active_header_block').style.display = 'block';

            // Display existing cover image if available
            const existingImage = document.getElementById('existingImage');
            if (imgUrl) {
                existingImage.src = imgUrl; // Set the image source to the URL passed from the backend

                document.getElementById('existingImageDiv').style.display = 'block';
                // existingImage.style.display = 'block'; // Ensure the image is visible

            } else {
                document.getElementById('existingImageDiv').style.display = 'none';
                // existingImage.style.display = 'none'; // Hide the image if no URL is passed
            }

            // Display the modal
            $('#createcampaign').modal('show');
        }
    </script>




@endsection
