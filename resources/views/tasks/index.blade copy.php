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
    <!-- Custom CSS Stylesheet -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/single-img-container.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('public/assets/css/single-img-container.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/profile-group-profile.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/profile-myprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/task-table-data-info.css') }}">
    <div class="task campaingn-table pb-3 common-table task-table-info">

        <!-- campaigns-contents -->
        <div class="col-lg-12 task campaigns-contents ">
            <div class="campaigns-title">
                <h3>TASKS</h3>
            </div>
            <form>
                <input type="text" name="search" placeholder="Search...">
                <a href="#" class="create-task-btn" data-toggle="modal" data-target="#createTask">Create Task</a>
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
            <table>
                <thead>
                    <tr>
                        <th class="campaingn-title">
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
                        <th class="active">
                            <span>Status</span>
                        </th>
                        <th class="action">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td class="campaingn-title">
                                <span>{{ $task->name }}</span>
                            </td>
                            <td>
                                <span>{{ $task->campaign ? $task->campaign->name : 'N/A' }}</span>
                            </td>
                            <td>
                                <span>{{ $task->date_required }}</span>
                            </td>
                            <td class="description">
                                <span>{{ $task->description }}</span>
                            </td>
                            <td class="active">
                                <span>{{ $task->status ? $task->status->name : 'N/A' }}</span>
                            </td>
                            <td class="action library-action task"><span>
                                    <div class="action-btn-icons ">
                                        {{-- <button class="btn search"><i class='bx bx-search-alt-2'></i></button> --}}
                                        <a href="{{ route('tasks.show', $task->id) }}" class="btn search"><i class="fa fa-eye" title="show"></i></a>

                                        <button class="btn edit"><i class='bx bx-edit'></i></button>

                                        <form id="Model-Form" action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"  class="btn delete"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </div>
                                </span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade createTask-modal" id="createTask" tabindex="-1" aria-labelledby="ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" >Create Task
                        </h1>
                    <p class="status green">Active</p>
                    <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="Model-Form" action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <div class="row m-0">
                            <div class="col-xl-4">
                                <select class="form-select" name="campaign_id" required aria-label="Default select example">
                                    <option selected>Select Campaign</option>
                                    @foreach ($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
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
                                    <input type="text" name="date_required" id="datepicker" required
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

                                <select class="form-select" name="category" required aria-label="Default select example">
                                    <option selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }} ">
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-xl-3 p-xl-0">
                                <div class="input-wrap">
                                    <input type="text" name="size_width" id="size_width" required
                                        placeholder="Size (Width)">
                                    <input type="text" name="size_height" id="size_height" required
                                        placeholder="Size (Height)">
                                </div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-md-12">
                                <label for="">Task Brief</label>
                                <textarea name="description" required id="description"></textarea>
                            </div>
                            <span class="info-text">Add a description for your Task</span>
                        </div>
                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                            <div class="sic-btn">
                                <button class="btn create-task" id="uploadAsset">
                                    upload assets
                                </button>
                            </div>
                            <div class="sic-btn">
                                <button class="btn link-asset" id="cancel" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    cancel
                                </button>
                            </div>
                            <div class="sic-btn">
                                <button class="btn download" id="save">
                                    save
                                </button>
                            </div>
                        </div>

                        <div class="img-upload-con d-none">
                            <div class="upload--col">
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
                            <div class="additional-img">
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
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- The Modal -->
    <div class="modal fade bd-example-modal-lg" id="createTaskModal123" tabindex="-1" role="dialog"
    aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Create Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="Model-Form" action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="campaign_id">Campaign</label>
                                <select name="campaign_id" id="campaign_id" class="form-control">
                                    <option value="">Select Campaign</option>
                                    @foreach ($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="status">
                                    <label for="task_urgent"><b>Status :</b> <span>STARTED</span></label>
                                    <input type="hidden" name="" id="" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Task Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_required">Date Required</label>
                                <input type="date" name="date_required" id="date_required" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="task_urgent">Urgent</label>
                                <input type="checkbox" name="task_urgent" id="task_urgent" class="check" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="asset_type">Asset Type:</label>
                                <input type="text" name="asset_type" disabled id="asset_type" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="size_width">Size (Width)</label>
                                <input type="number" name="size_width" id="size_width" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="size_height">Size (Height)</label>
                                <input type="number" name="size_height" id="size_height" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="description">Task Brief</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}


    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this task ?');
        }
    </script>

    <!-- Bootstrap 5.2 JS cdn link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
    <script src="assets/js/main.js"></script>
    



    <script>
        $(document).ready(function() {
            var scrollTop = $(".scrollTop");

            // Initialize the datepicker
            $("#datepicker").datepicker();

            // $("#uploadAsset").click(function(){

            //     $(".img-upload-con").toggleClass('d-none')
            // })
            $(document).on('click', '#uploadAsset', function(e) {
                $(".img-upload-con").toggleClass('d-none');


                e.stopPropagation();
                e.preventDefault();


            });
        });
    </script>
    

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
