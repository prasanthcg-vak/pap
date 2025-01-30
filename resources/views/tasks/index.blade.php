@extends('layouts.app')

@section('content')
    <style>
        .modal-dialog {
            max-width: 80%;
            /* Adjust this percentage as needed */
        }

        .check {
            margin: .5rem !important;
        }

        .status {
            float: right;
        }

        .status span {
            color: red;
        }
    </style>

    @php
        $showButton = Auth::user()->hasRolePermission('tasks.show');
        $createButton = Auth::user()->hasRolePermission('tasks.create');
        $editButton = Auth::user()->hasRolePermission('tasks.edit');
        $deleteButton = Auth::user()->hasRolePermission('tasks.destroy');
        $hasActionPermission = $showButton || $editButton || $deleteButton; // Check if any permission is granted

    @endphp
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <div class="task campaingn-table pb-3 ">

                <!-- campaigns-contents -->
                <div class="col-lg-12 task campaigns-contents ">
                    <div class="campaigns-title">
                        <h3>TASKS</h3>
                    </div>
                    <form>
                        @if ($createButton)
                            <a href="#" class="create-task-btn" data-toggle="modal" data-target="#createTask"
                                onclick="openModal()"><span>Create
                                    Task</span> <i class="fa-solid fa-plus"></i></a>
                        @endif
                        {{-- <input type="text" name="search" placeholder="Search..."> --}}

                    </form>
                </div>
                <!-- campaigns-contents -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="table-wrapper">
                    <table id="datatable" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                {{-- <th>Slno</th> --}}
                                <th class="">
                                    <span>Task Title</span>
                                </th>
                                <th>
                                    <span>Campaign</span>
                                </th>
                                <th>
                                    <span>Due Date</span>
                                </th>
                                <th class="description">
                                    <span>Description</span>
                                </th>
                                @if (Auth::user()->roles->first()->role_level != 5 && Auth::user()->roles->first()->role_level != 4)
                                    <th class="">
                                        <span>Client</span>
                                    </th>
                                @endif
                                <th class="">
                                    <span>Client Group</span>
                                </th>
                                @if (!in_array(Auth::user()->roles->first()->role_level, [4, 5, 6]))
                                    <th class="">
                                        <span>Staff</span>
                                    </th>
                                @endif
                                <th class="">
                                    <span>Assets</span>
                                </th>
                                <th class="">
                                    <span>Status</span>
                                </th>
                                @if ($hasActionPermission)
                                    <th class="action">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    {{-- <td></td> --}}
                                    @php
                                        // dd($task->task_urgent);
                                    @endphp
                                    <td class="">
                                        <span>{{ $task->name }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $task->campaign ? $task->campaign->name : 'N/A' }}</span>
                                    </td>
                                    <td class="{{ $task->task_urgent == 1 ? 'red' : '' }}">
                                        <span>{{ $task->date_required }}</span>
                                    </td>


                                    <td class="">
                                        @php
                                            $words = explode(' ', strip_tags($task->description)); // Strip HTML tags and split into words
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
                                                {!! $task->description !!}
                                                <span class="read-less"
                                                    style="color: orange; cursor: pointer; text-decoration: underline;">
                                                    Read
                                                    less</span>
                                            </span>
                                        @endif
                                    </td>
                                    @if (Auth::user()->roles->first()->role_level != 5 && Auth::user()->roles->first()->role_level != 4)
                                        <td class="">
                                            <span>{{ $task->campaign->client->name ?? '-' }}</span>
                                        </td>
                                    @endif
                                    <td class="">
                                        <span>{{ $task->campaign->group->name ?? '-' }} </span>
                                    </td>
                                    @if (!in_array(Auth::user()->roles->first()->role_level, [4, 5, 6]))
                                        <td class="">
                                            <span>-</span>
                                        </td>
                                    @endif
                                    <td>{{ $task->image_id ? '1' : '0' }}</td>
                                    <td class="">
                                        <span
                                            class="status {{ $task->is_active == 1 ? 'green' : 'red' }}">{{ $task->is_active == 1 ? 'ACTIVE' : 'INACTIVE' }}</span>
                                    </td>
                                    @if ($hasActionPermission)
                                        <td class="action library-action task">
                                            <span>
                                                <div class="action-btn-icons ">
                                                    {{-- <button class="btn search"><i class='bx bx-search-alt-2'></i></button> --}}
                                                    @if ($showButton)
                                                        <a href="{{ route('tasks.show', $task->id) }}"
                                                            class="btn search"><i class="fa fa-eye" title="show"></i></a>
                                                    @endif

                                                    <a href="#" class="edit-task-btn" data-id="{{ $task->id }}"
                                                        data-toggle="modal" data-target="#editTaskModal">
                                                        <button class="btn edit"><i class='bx bx-edit'></i></button>
                                                    </a>
                                                    @if ($deleteButton)
                                                        <form id="Model-Form"
                                                            action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                            style="display:inline;" onsubmit="return confirmDelete();">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn delete"><i
                                                                    class="bx bx-trash"></i></button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </span>
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>


            </div>
        </div>
    </div>
    <div class="modal fade createTask-modal" id="createTask" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
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
                    <form id="Model-Form" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row m-0">
                            <input type="hidden" id="method-field" name="_method" value="POST">
                            <div class="col-xl-4 col-md-6  mt-md-0 mt-4">
                                <label for="campaign-select">Campaign Name</label>
                                <select class="form-select" id="campaign-select" name="campaign_id" required
                                    aria-label="Default select example">
                                    <option value="" selected>Select Campaign</option>
                                    @foreach ($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}">
                                            {{ $campaign->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if (Auth::user()->roles->first()->role_level != 5 && Auth::user()->roles->first()->role_level != 4)
                                <div class="col-xl-4 col-md-6">
                                    <label for="client-name">Client Name</label>
                                    <input type="text" id="client-name" name="client_name" class="form-control"
                                        readonly>
                                </div>
                            @endif
                            @if (Auth::user()->roles->first()->role_level != 5 && Auth::user()->roles->first()->role_level != 4)
                                <div class="col-xl-4 col-md-6">
                                    <label for="group-name">Group Name</label>
                                    <input type="text" id="group-name" name="group_name" class="form-control"
                                        readonly>
                                </div>
                            @endif

                            <!-- Partner Dropdown -->


                        </div>

                        <div class="row m-0">
                            <div class="col-xl-4">
                                <label for="">Task Name</label>
                                <input type="text" name="name" id="" required placeholder="Task Name">
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
                                <select class="form-select" name="asset_id" required aria-label="Default select example">
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
        });

        function confirmDelete() {
            return confirm('Are you sure you want to delete this task ?');
        }

        function openModal() {
            const modal = $('#createTask');

            // Reset form
            modal.find('#Model-Form')[0].reset();
            modal.find('#Model-Form').attr('action', '{{ route('tasks.store') }}'); // Set Create action
            modal.find('#method-field').val('POST'); // Set method to POST

            $('#createTask').modal('show');
        }
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
    </script>

    <script>
        $(document).ready(function() {
            var scrollTop = $(".scrollTop");

            // Initialize the datepicker
            // $("#datepicker").datepicker();

            // $("#uploadAsset").click(function(){

            //     $(".img-upload-con").toggleClass('d-none')
            // })



        });

        document.addEventListener('DOMContentLoaded', function() {
            const campaignDropdown = document.getElementById('campaign-select');
            const partnerDropdown = document.getElementById('partner-select');
            const clientName = document.getElementById('client-name'); // The readonly field for client name
            const groupName = document.getElementById('group-name'); // The readonly field for group name

            // Handle Campaign Selection
            campaignDropdown.addEventListener('change', function() {
                $('#modalLoader').show();
                const campaignId = this.value;
                if (clientName) {
                    clientName.value = '';
                }
                if (groupName) {
                    groupName.value = '';
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
                            if (groupName) {
                                groupName.value = data.group?.name || 'No Group';
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

        // $('#campaign-select').on('change', function() {
        //     const campaignId = $(this).val();
        //     const $partnerSelect = $('#partner-select');
        //     $('#modalLoader').show();

        //     // Clear existing options
        //     $partnerSelect.html('<option value="" selected>Select Partner</option>').prop('disabled', true);

        //     if (campaignId) {
        //         // Fetch partners based on the selected campaign
        //         $.ajax({
        //             url: `/partner/${campaignId}`,
        //             method: 'GET',
        //             dataType: 'json',
        //             success: function(data) {
        //                 $('#modalLoader').hide();
        //                 console.log(data); // Log data to inspect the structure
        //                 if (data.length > 0) {
        //                     $.each(data, function(index, partner) {
        //                         const option = $('<option>', {
        //                             value: partner
        //                             .id, // Assuming 'id' is the primary key
        //                             text: partner.partner ? partner.partner.name :
        //                                 'Unnamed Partner', // Fallback for null partner
        //                         });
        //                         $partnerSelect.append(option);
        //                     });
        //                     $partnerSelect.prop('disabled', false);
        //                 }
        //             },
        //             error: function(xhr, status, error) {
        //                 $('#modalLoader').hide();
        //                 console.error('Error fetching partners:', error);
        //             },
        //         });
        //     }
        // });
    </script>

    <!-- Include Bootstrap JS and dependencies -->
@endsection
