@extends('layouts.app')

@section('content')

    <style>
        .img-container .image-wrapper {
            height: 150px;
            /* Set a fixed height for consistent display */
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
            <div class="campaign-card-contents">
                <div class="col-lg-12 p-0">
                    <div class="card">
                        <div class="heading_text">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary ms-4" style="float: right;">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            <div class="title_status">
                                @if (!in_array(Auth::user()->roles->first()->role_level, [1, 3]))
                                    <h3>{{ $campaign->name }}</h3>
                                @else
                                    <h3>{{ $campaign->client->name }} : {{ $campaign->name }}</h3>
                                @endif
                                <p class="status {{ $campaign->is_active ? 'green' : 'red' }} mt-1">
                                    {{ $campaign->is_active ? 'Active' : 'Inactive' }}
                                </p>

                            </div>
                            <div class="title_status">
                                <h3>Staff:<span>
                                    {{ $campaignStaffs->pluck('staff.name')->filter()->implode(', ') ?: 'N/A' }}
                                    </span></h3>
                                </div>
                                <p>{!! $campaign->description !!}</p>
                            </div>
                            <!-- campaign-cost-task -->
                            {{-- <div class="campaign-cost-task">
                        <div class="col-lg-12">
                            <div class="campaign-cost-task-header">
                                <h3>CAMPAIGN TASK COST TO DATE: $XXX</h3>
                                <div class="icons d-flex align-items-center gap-5">
                                    <button class="btn btn-default p-0 " style="width:unset">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_61_11752)">
                                                <path
                                                    d="M23.7068 22.2937L17.7378 16.3247C19.3644 14.3353 20.1642 11.7968 19.9716 9.23427C19.7791 6.67174 18.609 4.28124 16.7034 2.55723C14.7977 0.83322 12.3024 -0.0923988 9.73342 -0.028167C7.16447 0.0360648 4.71849 1.08523 2.9014 2.90232C1.08431 4.71941 0.0351379 7.1654 -0.029094 9.73435C-0.0933258 12.3033 0.832293 14.7987 2.5563 16.7043C4.28031 18.6099 6.67081 19.78 9.23334 19.9726C11.7959 20.1651 14.3344 19.3653 16.3238 17.7387L22.2928 23.7077C22.4814 23.8899 22.734 23.9907 22.9962 23.9884C23.2584 23.9861 23.5092 23.8809 23.6946 23.6955C23.88 23.5101 23.9852 23.2593 23.9875 22.9971C23.9897 22.7349 23.8889 22.4823 23.7068 22.2937ZM9.99978 18.0007C8.41753 18.0007 6.87081 17.5315 5.55522 16.6525C4.23963 15.7734 3.21425 14.524 2.60875 13.0622C2.00324 11.6004 1.84482 9.99184 2.1535 8.43999C2.46218 6.88814 3.22411 5.46268 4.34293 4.34385C5.46175 3.22503 6.88721 2.46311 8.43906 2.15443C9.99091 1.84574 11.5994 2.00417 13.0613 2.60967C14.5231 3.21517 15.7725 4.24055 16.6515 5.55615C17.5306 6.87174 17.9998 8.41846 17.9998 10.0007C17.9974 12.1217 17.1538 14.1552 15.654 15.6549C14.1542 17.1547 12.1208 17.9983 9.99978 18.0007Z"
                                                    fill="#EB8205" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_61_11752">
                                                    <rect width="24" height="24" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </button>
                                    <button class="btn btn-default p-0 " style="width:unset">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_61_11757)">
                                                <path
                                                    d="M23.9997 11.2472C23.855 8.9072 23.0288 6.66069 21.6229 4.78461C20.2169 2.90852 18.2927 1.48488 16.0874 0.689177C13.8821 -0.106521 11.4922 -0.239484 9.21222 0.306678C6.93229 0.85284 4.86201 2.05425 3.25662 3.7628C1.65122 5.47135 0.580905 7.61235 0.177606 9.92184C-0.225693 12.2313 0.0556603 14.6084 0.98698 16.7599C1.9183 18.9114 3.45887 20.7434 5.41877 22.0299C7.37867 23.3165 9.67222 24.0014 12.0167 24.0002H18.9997C20.3253 23.9989 21.5963 23.4717 22.5337 22.5343C23.4711 21.5969 23.9983 20.3259 23.9997 19.0002V11.2472ZM21.9997 19.0002C21.9997 19.7958 21.6836 20.5589 21.121 21.1215C20.5584 21.6841 19.7953 22.0002 18.9997 22.0002H12.0167C10.6056 21.9995 9.21051 21.7015 7.92236 21.1255C6.63421 20.5495 5.48194 19.7084 4.54066 18.6572C3.59484 17.6065 2.88459 16.3657 2.45756 15.018C2.03052 13.6703 1.89656 12.2469 2.06466 10.8432C2.3301 8.62912 3.32485 6.56637 4.89211 4.98011C6.45936 3.39384 8.50997 2.37429 10.7207 2.08218C11.1519 2.02813 11.5861 2.00075 12.0207 2.00018C14.3511 1.99383 16.6095 2.80808 18.3997 4.30018C19.4452 5.16913 20.3034 6.24133 20.9222 7.45181C21.541 8.66229 21.9076 9.98582 21.9997 11.3422V19.0002Z"
                                                    fill="#EB8205" />
                                                <path
                                                    d="M8 8.99984H12C12.2652 8.99984 12.5196 8.89448 12.7071 8.70694C12.8946 8.51941 13 8.26505 13 7.99984C13 7.73462 12.8946 7.48027 12.7071 7.29273C12.5196 7.1052 12.2652 6.99984 12 6.99984H8C7.73478 6.99984 7.48043 7.1052 7.29289 7.29273C7.10536 7.48027 7 7.73462 7 7.99984C7 8.26505 7.10536 8.51941 7.29289 8.70694C7.48043 8.89448 7.73478 8.99984 8 8.99984Z"
                                                    fill="#EB8205" />
                                                <path
                                                    d="M16 11.0002H8C7.73478 11.0002 7.48043 11.1055 7.29289 11.2931C7.10536 11.4806 7 11.7349 7 12.0002C7 12.2654 7.10536 12.5197 7.29289 12.7073C7.48043 12.8948 7.73478 13.0002 8 13.0002H16C16.2652 13.0002 16.5196 12.8948 16.7071 12.7073C16.8946 12.5197 17 12.2654 17 12.0002C17 11.7349 16.8946 11.4806 16.7071 11.2931C16.5196 11.1055 16.2652 11.0002 16 11.0002Z"
                                                    fill="#EB8205" />
                                                <path
                                                    d="M16 15H8C7.73478 15 7.48043 15.1054 7.29289 15.2929C7.10536 15.4804 7 15.7348 7 16C7 16.2652 7.10536 16.5196 7.29289 16.7071C7.48043 16.8947 7.73478 17 8 17H16C16.2652 17 16.5196 16.8947 16.7071 16.7071C16.8946 16.5196 17 16.2652 17 16C17 15.7348 16.8946 15.4804 16.7071 15.2929C16.5196 15.1054 16.2652 15 16 15Z"
                                                    fill="#EB8205" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_61_11757">
                                                    <rect width="24" height="24" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </button>



                                </div>
                            </div>
                        </div>
                    </div> --}}
                            <!-- campaign-cost-task -->

                            <!-- Owl carousel -->

                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-9 col-md-10 col-sm-10 p-0">
                                    @if (!empty($imageUrls) && count($imageUrls) > 0)
                                        <div class="owl-carousel owl-theme">
                                            @foreach ($imageUrls as $img)
                                                @php
                                                    $thumbnail = match ($img['image_type']) {
                                                        'image' => $img['url'],
                                                        'video' => $img['thumbnail'],
                                                        default => $img['thumbnail'],
                                                    };
                                                @endphp
                                                <div class="item py-3">
                                                    <a
                                                        href="{{ route('campaigns.assetsview', ['id' => $img['image_id']]) }}">
                                                        <div class="card-img_text">
                                                            <div class="Detail-card-image">
                                                                <img src="{{ $thumbnail }}" alt="{{ $img['name'] }}"
                                                                    class="w-100">
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="sic-img-info">
                                            <span>No Images</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="campaigns-title">
                                <h3>{{ $campaign->name }} - TASKS</h3>
                            </div>
                            <div class="campaingn-table common-table">
                                <div class="table-wrapper">
                                    <table>
                                        <thead>
                                            <tr>

                                                <th class="campaingn-title">
                                                    <span>Task Title</span>
                                                </th>
                                                {{-- <th>
                                            <span>Campaign</span>
                                        </th> --}}
                                                <th>
                                                    <span>Due Date</span>
                                                </th>
                                                <th class="description">
                                                    <span>description</span>
                                                </th>
                                                @if (Auth::user()->roles->first()->role_level != 5 && Auth::user()->roles->first()->role_level != 4)
                                                    <th>Client Group</th>
                                                @endif
                                                @if (!in_array(Auth::user()->roles->first()->role_level, [4, 5, 6]))
                                                    <th class="">
                                                        <span>Staff</span>
                                                    </th>
                                                @endif
                                                <th class="">
                                                    <span>active</span>
                                                </th>
                                                <th class="">
                                                    <span>action</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tasks as $task)
                                                <tr>
                                                    <td class="campaingn-title">
                                                        <span>{{ $task->name }}</span>
                                                    </td>
                                                    {{-- <td>
                                            <span>{{$campaign->name}}</span>
                                        </td> --}}
                                                    <td>
                                                        <span>{{ $task->date_required }}</span>
                                                    </td>
                                                    <td class="description">
                                                        <span>{!! $task->description !!}
                                                        </span>
                                                    </td>
                                                    @if (Auth::user()->roles->first()->role_level != 5 && Auth::user()->roles->first()->role_level != 4)
                                                        <td class="">
                                                            <span>{{ $task->campaign->group->name ?? '-' }}</span>
                                                        </td>
                                                    @endif
                                                    @if (!in_array(Auth::user()->roles->first()->role_level, [4, 5, 6]))
                                                        <td>
                                                            staff
                                                        </td>
                                                    @endif

                                                    <td class="">
                                                        <span>{{ optional($task->status)->name ?? 'No Status' }}</span>
                                                    </td>
                                                    <td class="">
                                                        <span>
                                                            <div class="action-btn-icons ">
                                                                <a href="{{ route('tasks.show', $task->id) }}"
                                                                    class="btn search"><i class="fa fa-eye"
                                                                        title="show"></i></a>

                                                                <a href="#" class="edit-task-btn"
                                                                    data-id="{{ $task->id }}" data-toggle="modal"
                                                                    data-target="#editTaskModal">
                                                                    <button class="btn edit"><i
                                                                            class='bx bx-edit'></i></button>
                                                                </a>
                                                            </div>
                                                        </span>
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
                </div>
                <!-- Pagination -->
                <div class="card-pagination">

                </div>
                <!-- Pagination -->
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
                        <span class="btn-close" id="model-close" data-dismiss="modal" aria-label="Close"></span>
                    </div>
                    <div id="modalLoader" class="modal-loader" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form id="Model-Form" action="{{ route('tasks.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row m-0">
                                <input type="hidden" id="method-field" name="_method" value="POST">
                                <div class="col-xl-4 col-md-6  mt-md-0 mt-4">
                                    <label for="campaign-select">Campaign Name</label>
                                    <select class="form-select" id="campaign-select" name="campaign_id" required
                                        aria-label="Default select example">
                                        <option value="" selected>Select Campaign</option>
                                        {{-- @foreach ($campaigns as $campaign) --}}
                                        <option value="{{ $campaign->id }}">
                                            {{ $campaign->name }}
                                        </option>
                                        {{-- @endforeach --}}
                                    </select>
                                </div>
                                @if (Auth::user()->roles->first()->role_level != 5 && Auth::user()->roles->first()->role_level != 4)
                                    <div class="col-xl-4 col-md-6">
                                        <label for="client-name">Client Name</label>
                                        <input type="text" id="client-name" name="client_name" class="form-control"
                                            readonly>
                                    </div>
                                @endif

                                <!-- Partner Dropdown -->
                                @if (Auth::user()->roles->first()->role_level == 6)
                                    <input type="hidden" name="partner_id" value="{{ Auth::id() }}">
                                @else
                                    <div class="col-xl-4 col-md-6 mt-md-0 mt-4">
                                        <label for="partner-select">Select Campaign Partners</label>
                                        <select class="form-select" id="partner-select" name="partner_id" required
                                            aria-label="Default select example">
                                            <option value="" selected>Select Partner</option>
                                        </select>
                                    </div>
                                @endif

                            </div>

                            <div class="row m-0">
                                <div class="col-xl-4">
                                    <label for="">Task Name</label>
                                    <input type="text" name="name" id="" required
                                        placeholder="Task Name">
                                </div>
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
                                    <label for="">Category</label>
                                    <select class="form-select" name="category_id" required
                                        aria-label="Default select example">
                                        <option value="" selected>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                    <label for="">Asset Type</label>
                                    <select class="form-select" name="asset_id" required
                                        aria-label="Default select example">
                                        <option value="" selected>Select Asset</option>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}">
                                                {{ $asset->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                    <label for="">Width</label>
                                    <input type="number" name="size_width" id="size_width" required
                                        placeholder="Size (Width)">
                                </div>
                                <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                    <label for="">Height</label>
                                    <input type="number" name="size_height" id="size_height" required
                                        placeholder="Size (Height)">
                                </div>
                                <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                    <label for="">Measurement</label>
                                    <input type="text" name="size_measurement" id="size_measurement" required
                                        placeholder="Size Measurement">
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="col-md-12">
                                    <label for="">Task Brief</label>
                                    <textarea name="description" id="editor"></textarea>
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
                                                    Asset</span>
                                            </div>
                                            <div class="file-format">
                                                <p>File Format <b>JEPG, PNG, JPG, MP4, PDF</b></p>
                                            </div>
                                        </div>
                                        <input type="file" name="myFile" class="drop-zone__input">
                                    </div>
                                </div>
                            </div>



                        </form>
                    </div>
                    <div id="modalLoader" class="modal-loader" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    @endsection

    @section('script')
        <script>
            $(document).on('click', '.edit-task-btn', function() {
                const taskId = $(this).data('id');
                const modal = $('#createTask');
                modal.modal('show');


                $('#modalLoader').show(); // Show loader

                $.ajax({
                    url: `/tasks/${taskId}/edit`, // Replace with your route
                    method: 'GET',
                    success: function(response) {
                        const form = modal.find('#Model-Form');
                        form.attr('action', `/tasks/${taskId}`); // Update action to tasks.update route
                        modal.find('#method-field').val('PUT');

                        const partnerSelect = modal.find('#partner-select');
                        partnerSelect.empty(); // Clear existing options
                        partnerSelect.append(
                            '<option value="" selected>Select Partner</option>'); // Default option
                        response.partners.forEach(function(partner) {
                            partnerSelect.append(
                                `<option value="${partner.id}" ${response.task.partner_id == partner.id ? 'selected' : ''}>
                        ${partner.name}
                    </option>`
                            );
                        });
                        $('#modalLoader').hide();

                        // Populate the modal with data
                        modal.find('#campaign-select').val(response.task.campaign_id);
                        modal.find('#client-name').val(response.client_name); // Client name
                        modal.find('#partner-select').val(response.task.partner_id);
                        modal.find('input[name="name"]').val(response.task.name);
                        modal.find('input[name="date_required"]').val(response.task.date_required);
                        modal.find('input[name="task_urgent"]').prop('checked', response.task.task_urgent);
                        modal.find('select[name="category_id"]').val(response.task.category_id);
                        modal.find('select[name="asset_id"]').val(response.task.asset_id);
                        modal.find('#size_width').val(response.task.size_width);
                        modal.find('#size_height').val(response.task.size_height);
                        modal.find('#size_measurement').val(response.task.size_measurement);
                        modal.find('textarea[name="description"]').val(response.task.description);
                        modal.find('input[name="is_active"]').prop('checked', response.task.is_active);

                    },
                    error: function() {
                        $('#modalLoader').hide();
                        alert('Failed to fetch task details.');
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const campaignDropdown = document.getElementById('campaign-select');
                const partnerDropdown = document.getElementById('partner-select');
                const clientName = document.getElementById('client-name'); // The readonly field for client name

                // Handle Campaign Selection
                campaignDropdown.addEventListener('change', function() {
                    $('#modalLoader').show();
                    const campaignId = this.value;
                    if (clientName) {
                        clientName.value = '';
                    }

                    if (campaignId) {
                        if (partnerDropdown) {
                            partnerDropdown.disabled = true; // Disable while fetching
                        }
                        fetch(`/get-partners-by-campaign/${campaignId}`)
                            .then(response => response.json())
                            .then(data => {
                                // Populate Client Name
                                if (clientName) {
                                    clientName.value = data.client?.name || 'No Client';
                                }
                                // Populate Partner Dropdown
                                if (partnerDropdown) {
                                    partnerDropdown.innerHTML =
                                        `<option value="" selected>Select Partner</option>`;
                                    if (data.partners.length > 0) {
                                        data.partners.forEach(partner => {
                                            partnerDropdown.innerHTML +=
                                                `<option value="${partner.id}">${partner.partner.name}</option>`;
                                        });
                                    } else {
                                        $('#modalLoader').hide();
                                        alert('No partners found for the selected group.');
                                    }
                                    partnerDropdown.disabled = false; // Enable after loading
                                }
                                $('#modalLoader').hide();

                            })
                            .catch(() => {
                                alert('Failed to fetch partners. Please try again.');
                                if (partnerDropdown) {
                                    partnerDropdown.disabled = false;
                                }

                                $('#modalLoader').hide();
                            });
                    }



                });
            });
        </script>
    @endsection
