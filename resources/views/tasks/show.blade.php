@extends('layouts.app')
@section('content')

    <style>
        .card-shadow {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }

        .media {
            align-items: flex-start;
        }

        .media img {
            border: 2px solid #ddd;
            padding: 2px;
        }

        .media-body h5,
        .media-body h6 {
            font-size: 1rem;
            font-weight: bold;
            color: #333;
        }

        .media-body p {
            color: #555;
            margin: 0;
        }

        .delete-reply {
            padding: 4px 8px;
        }

        .replyForm input {
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.9rem;
        }

        .replyForm button {
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        small.text-muted {
            font-size: 0.8rem;
        }
    </style>
    @php
        $showButton = Auth::user()->hasRolePermission('comments.index');
        $storeButton = Auth::user()->hasRolePermission('comments.store');
        $editButton = Auth::user()->hasRolePermission('comments.edit');
        $deleteButton = Auth::user()->hasRolePermission('comments.destroy');
    @endphp
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <div class="campaign-card-contents task-table-info ">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_211_479)">
                                        <path
                                            d="M7.99967 14.6667C11.6816 14.6667 14.6663 11.6819 14.6663 7.99999C14.6663 4.3181 11.6816 1.33333 7.99967 1.33333C4.31778 1.33333 1.33301 4.3181 1.33301 7.99999C1.33301 11.6819 4.31778 14.6667 7.99967 14.6667Z"
                                            stroke="#EB8205" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_211_479">
                                            <rect width="16" height="16" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <span>Tasks</span>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.66602 7.33351H10.7795L7.05302 3.60701L7.99952 2.66701L13.333 8.00051L7.99952 13.334L7.05952 12.394L10.7795 8.66751H2.66602V7.33351Z"
                                        fill="#A1AEBE" />
                                </svg>
                            </a></li>
                        <li class="breadcrumb-item"><a href="#">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_211_479)">
                                        <path
                                            d="M7.99967 14.6667C11.6816 14.6667 14.6663 11.6819 14.6663 7.99999C14.6663 4.3181 11.6816 1.33333 7.99967 1.33333C4.31778 1.33333 1.33301 4.3181 1.33301 7.99999C1.33301 11.6819 4.31778 14.6667 7.99967 14.6667Z"
                                            stroke="#EB8205" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_211_479">
                                            <rect width="16" height="16" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <span>{{ $task->campaign->name ?? 'N/A' }}</span>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.66602 7.33351H10.7795L7.05302 3.60701L7.99952 2.66701L13.333 8.00051L7.99952 13.334L7.05952 12.394L10.7795 8.66751H2.66602V7.33351Z"
                                        fill="#A1AEBE" />
                                </svg>
                            </a></li>
                        <li class="breadcrumb-item"><a href="#">
                                <svg width="16" height="5" viewBox="0 0 16 5" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.00025 -0.000244141C9.0765 -0.000244141 10.0125 0.935756 10.0125 2.01201C10.0125 3.08826 9.07725 4.02426 8.00025 4.02426C6.924 4.02426 5.988 3.08901 5.988 2.01201C5.988 0.935006 6.924 -0.000244141 8.00025 -0.000244141ZM13.9882 -0.000244141C15.0645 -0.000244141 16.0005 0.935756 16.0005 2.01201C16.0005 3.08826 15.0652 4.02426 13.9882 4.02426C12.912 4.02426 11.976 3.08901 11.976 2.01201C11.976 0.935006 12.9112 -0.000244141 13.9882 -0.000244141ZM2.01225 -0.000244141C3.0885 -0.000244141 4.0245 0.935756 4.0245 2.01201C4.0245 3.08826 3.0885 4.02426 2.01225 4.02426C0.936 4.02426 0 3.08901 0 2.01201C0 0.935006 0.936 -0.000244141 2.01225 -0.000244141Z"
                                        fill="#EB8205" />
                                </svg>

                            </a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_211_479)">
                                    <path
                                        d="M7.99967 14.6667C11.6816 14.6667 14.6663 11.6819 14.6663 7.99999C14.6663 4.3181 11.6816 1.33333 7.99967 1.33333C4.31778 1.33333 1.33301 4.3181 1.33301 7.99999C1.33301 11.6819 4.31778 14.6667 7.99967 14.6667Z"
                                        stroke="#EB8205" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_211_479">
                                        <rect width="16" height="16" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <span>Task #{{ $task->id }} - {{ $task->name }}</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.66602 7.33351H10.7795L7.05302 3.60701L7.99952 2.66701L13.333 8.00051L7.99952 13.334L7.05952 12.394L10.7795 8.66751H2.66602V7.33351Z"
                                    fill="#A1AEBE" />
                            </svg>
                        </li>
                    </ol>
                </nav>
                <div class="col-lg-12 p-0">
                    <div class="card">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="heading_text">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary ms-4" style="float: right;">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            <div class="title_status">

                                <h3> {{ strtoupper($task->name ?? 'N/A') }}</h3>
                                <p class="status {{ $task->is_active == 1 ? 'green' : 'red' }} ">
                                    {{ $task->is_active == 1 ? 'ACTIVE' : 'INACTIVE' }} </p>
                            </div>
                        </div>
                        <!-- Task info details -->
                        <div class="task-info-details">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="campaign-fields">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Campaign:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <p>{{ $task->campaign->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="date-required-fields">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Date Required:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <p>{{ $task->date_required ?? 'N/A' }} (12 days to go)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Asset-fields">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Asset Type:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <p>{{ $task->asset()->first()->type_name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="Urgent-fields">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Urgent:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <p>{{ $task->task_urgent ? 'Yes' : 'No' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Category-fields">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Category:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <p>{{ $task->category->category_name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="Category-fields">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Dimensions:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <p>{{ $task->size_width ?? 'N/A' }}(w) x
                                                    {{ $task->size_height ?? 'N/A' }}(h) {{ $task->size_measurement }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="Task-brief-fields">
                                        <label>Task Brief:</label>
                                        <p>{!! $task->description ??
                                            '<span style="color: gray; font-style: italic;">No description available for this task.</span>' !!}</p>
                                    </div>
                                    {{-- <div class="upload-contents">
                                        <label>Uploads:</label>
                                        <div class="upload-content-links">
                                            <a href="#">Stockimage01.jpg</a>
                                            <a href="#">Worddocument.doc</a>
                                            <a href="#">example.pdf</a>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <!-- Task info details -->
                        <!-- Table -->
                        <div class="task campaingn-table pb-3 common-table">
                            <!-- campaigns-contents -->
                            <div class="col-lg-12 task campaigns-contents">
                                <div class="campaigns-title">
                                    <h3>VERSIONING</h3>
                                </div>

                            </div>
                            <!-- campaigns-contents -->
                            <div class="table-wrapper tsk-tbl-data">
                                <table class="list-view">
                                    <thead>
                                        <tr>
                                            <th class="">
                                                <span>thumbnail</span>
                                            </th>
                                            <th>
                                                <span>Date/Time</span>
                                            </th>
                                            <th class="">
                                                <span>Description</span>
                                            </th>
                                            <th>
                                                <span>status</span>
                                            </th>
                                            <th class="">
                                                <span>action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            {{-- <td class="library-img">
                                                <span><img class="img-fluid" src="public/assets/images/profile-image.svg"
                                                        alt=""></span>
                                            </td>
                                            <td>
                                                <span>4/08/24 15:23</span>
                                            </td>
                                            <td class="">
                                                <span>Ne vendit es molo quam qui am cum... </span>
                                            </td>
                                            <td>
                                                <span>Working</span>
                                            </td>
                                            <td class="library-action task">
                                                <div class="action-btn-icons">
                                                    <button class="btn search"><i class='bx bx-search-alt-2'></i></button>
                                                    <button class="btn edit"><i class='bx bx-edit'></i></button>
                                                    <button class="btn comment"><i
                                                            class='bx bx-message-dots'></i></button>
                                                    <button class="btn thumbs-up"><i
                                                            class="fa-solid fa-thumbs-up"></i></button>
                                                </div>
                                            </td> --}}
                                            <td style="text-align: center;">No data found</td>
                                        </tr>
                                        {{-- <tr>
                                            <td class="library-img">
                                                <span><img class="img-fluid"
                                                        src="assets/images/automated-prompt-generation-with-generative-ai 1.png"
                                                        alt=""></span>
                                            </td>
                                            <td>
                                                <span>4/08/24 15:23</span>
                                            </td>
                                            <td class="">
                                                <span>Ne vendit es molo quam qui am cum... </span>
                                            </td>
                                            <td>
                                                <span>Working</span>
                                            </td>
                                            <td class="library-action task">
                                                <div class="action-btn-icons">
                                                    <button class="btn search"><i class='bx bx-search-alt-2'></i></button>
                                                    <button class="btn edit"><i class='bx bx-edit'></i></button>
                                                    <button class="btn comment"><i
                                                            class='bx bx-message-dots'></i></button>
                                                    <button class="btn thumbs-up"><i
                                                            class="fa-solid fa-thumbs-up"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="library-img">
                                                <span><img class="img-fluid"
                                                        src="assets/images/automated-prompt-generation-with-generative-ai 1.png"
                                                        alt=""></span>
                                            </td>
                                            <td>
                                                <span>4/08/24 15:23</span>
                                            </td>
                                            <td class="">
                                                <span>Ne vendit es molo quam qui am cum... </span>
                                            </td>
                                            <td>
                                                <span>Working</span>
                                            </td>
                                            <td class="library-action task">
                                                <div class="action-btn-icons">
                                                    <button class="btn search"><i class='bx bx-search-alt-2'></i></button>
                                                    <button class="btn edit"><i class='bx bx-edit'></i></button>
                                                    <button class="btn comment"><i
                                                            class='bx bx-message-dots'></i></button>
                                                    <button class="btn thumbs-up"><i
                                                            class="fa-solid fa-thumbs-up"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="library-img">
                                                <span><img class="img-fluid"
                                                        src="assets/images/automated-prompt-generation-with-generative-ai 1.png"
                                                        alt=""></span>
                                            </td>
                                            <td>
                                                <span>4/08/24 15:23</span>
                                            </td>
                                            <td class="">
                                                <span>Ne vendit es molo quam qui am cum... </span>
                                            </td>
                                            <td>
                                                <span>Working</span>
                                            </td>
                                            <td class="library-action task">
                                                <div class="action-btn-icons">
                                                    <button class="btn search"><i class='bx bx-search-alt-2'></i></button>
                                                    <button class="btn edit"><i class='bx bx-edit'></i></button>
                                                    <button class="btn comment"><i
                                                            class='bx bx-message-dots'></i></button>
                                                    <button class="btn thumbs-up"><i
                                                            class="fa-solid fa-thumbs-up"></i></button>
                                                </div>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- Table -->
                        <!-- Comments -->
                        @if ($showButton || $storeButton || $deleteButton || $editButton)
                            @if ($showButton)
                                <div class="comments-section">
                                    <div class="title_status mt-3">
                                        <h3>COMMENTS</h3>
                                    </div>

                                    <!-- Comment Form -->
                                    @if ($storeButton)
                                        <form id="commentForm" method="POST">
                                            @csrf
                                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                                            <div class="comments-header">
                                                <div class="profile-fields">
                                                    <img src="{{ asset('/assets/images/profile-image.svg') }}"
                                                        alt="profile-image">
                                                </div>
                                                <div class="comment-input-fields">
                                                    <input type="text" name="contents" placeholder="Add a comment"
                                                        required>
                                                </div>
                                                <div class="comments-button">
                                                    <button type="submit" class="comments-btn">comment</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                    <!-- Comments Display Section -->
                                    <div id="comments-list" class="mt-3 mb-3 container-fluid p-0">
                                        <div class="card card-shadow p-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @foreach ($task->comments as $comment)
                                                            <div class="media mb-4 border-bottom pb-3">
                                                                <img class="mr-3 rounded-circle" alt="User Profile Image"
                                                                    src="{{ asset('/assets/images/profile-image.svg') }}"
                                                                    style="width: 50px; height: 50px;" />
                                                                <div class="media-body">
                                                                    <div class="d-flex justify-content-between">
                                                                        <div>
                                                                            <h5 class="mb-0">{{ $comment->user->name }}
                                                                            </h5>
                                                                            <small
                                                                                class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                        <div>
                                                                            <button
                                                                                class="btn btn-secondary btn-sm toggle-reply"><i
                                                                                    class="fas fa-reply"></i>
                                                                                Reply</button>
                                                                            {{-- <button
                                                                                class="btn btn-warning btn-sm toggle-edit"><i
                                                                                    class="fas fa-edit"></i> Edit</button> --}}
                                                                            @if (Auth::user()->roles->first()->role_level != 3)
                                                                                <button
                                                                                    class="btn btn-danger btn-sm delete-reply"
                                                                                    data-id="{{ $comment->id }}"><i
                                                                                        class="fas fa-trash"></i></button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <p class="mt-2 comment-content">
                                                                        {{ $comment->content }}</p>

                                                                    <!-- Display Replies -->
                                                                    @foreach ($comment->replies as $reply)
                                                                        <div class="media mt-4">
                                                                            <img class="mr-3 rounded-circle"
                                                                                alt="User Profile Image"
                                                                                src="{{ asset('/assets/images/profile-image.svg') }}"
                                                                                style="width: 40px; height: 40px;" />
                                                                            <div class="media-body">
                                                                                <div
                                                                                    class="d-flex justify-content-between">
                                                                                    <div>
                                                                                        <h6 class="mb-0">
                                                                                            {{ $reply->user->name }}</h6>
                                                                                        <small
                                                                                            class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                                                    </div>
                                                                                    <div>
                                                                                        @if (Auth::user()->roles->first()->role_level != 3)
                                                                                            <button
                                                                                                class="btn btn-danger btn-sm delete-reply"
                                                                                                data-id="{{ $reply->id }}">
                                                                                                <i
                                                                                                    class="fas fa-trash"></i>
                                                                                            </button>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                <p class="mt-2">{{ $reply->content }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach

                                                                    <!-- Reply Form (Initially Hidden) -->
                                                                    <div class="reply-section mt-3"
                                                                        style="display: none;">
                                                                        <form class="replyForm d-flex align-items-center"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="task_id"
                                                                                value="{{ $task->id }}">
                                                                            <input type="hidden" name="parent_id"
                                                                                value="{{ $comment->id }}">
                                                                            <input type="text"
                                                                                class="form-control me-2" name="contents"
                                                                                placeholder="Add a reply" required>
                                                                            <button type="submit"
                                                                                class="btn btn-primary"><i
                                                                                    class="fas fa-paper-plane"></i></button>
                                                                        </form>
                                                                    </div>

                                                                    <!-- Edit Form (Initially Hidden) -->
                                                                    <div class="edit-section mt-3" style="display: none;">
                                                                        <form class="editForm d-flex align-items-center"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="comment_id"
                                                                                value="{{ $comment->id }}">
                                                                            <input type="text"
                                                                                class="form-control me-2 edit-input"
                                                                                name="updated_content"
                                                                                value="{{ $comment->content }}" required>
                                                                            <button type="submit"
                                                                                class="btn btn-success"><i
                                                                                    class="fas fa-save"></i> Save</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            @endif
                        @endif



                        <!-- Comments -->
                        <!-- campaign task cost -->
                        <div class="campaign-task-cost py-3">
                            <div class="campaign-task-cost-title">
                                <button type="button" class="btn view-btn" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal2">
                                    CAMPAIGN TASK COST TO DATE: $XXX
                                </button>
                            </div>
                            <div class="campaign-task-cost-button">
                                @if (Auth::user()->hasRolePermission('tasks.edit'))
                                    <a href="#" class="btn edit-task-btn" data-toggle="modal"
                                        data-target="#createTask" onclick="openModal()">Edit
                                        Task</a>
                                @endif
                                {{-- <a href="#" class="btn complete-task-btn">Complete Task</a> --}}
                            </div>
                        </div>
                        <!-- campaign task cost -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade editTask-modal" id="createTask" tabindex="-1" aria-labelledby="editTaskLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editTaskLabel">Edit Task</h1>
                    <p class="status green">Active</p>
                    <span class="btn-close" data-dismiss="modal" id="model-close" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form id="Model-Form" action="{{ route('tasks.update', $task->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- HTTP method for updating -->

                        <!-- Campaign and Partner -->
                        <div class="row m-0">
                            <div class="col-xl-4 col-md-6  mt-md-0 mt-4">
                                <label for="campaign-select">Campaign Name</label>
                                <select class="form-select" id="campaign-select" name="campaign_id" required
                                    aria-label="Default select example">
                                    <option value="" selected>Select Campaign</option>
                                    @foreach ($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}"
                                            {{ $task->campaign_id == $campaign->id ? 'selected' : '' }}>
                                            {{ $campaign->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <label for="client-name">Client Name</label>
                                <input type="text" id="client-name" name="client_name" class="form-control" readonly
                                    value="{{ $partners->isNotEmpty() && $partners->first()->campaign && $partners->first()->campaign->client ? $partners->first()->campaign->client->name : 'No client' }}">
                            </div>

                            <!-- Partner Dropdown -->
                            @if (Auth::user()->roles->first()->role_level == 6)
                                <input type="hidden" name="partner_id" value="{{ Auth::id() }}">
                            @else
                                <div class="col-xl-4 col-md-6 mt-md-0 mt-4">
                                    <label for="partner-select">Select Campaign Partners</label>
                                    <select class="form-select" id="partner-select" name="partner_id" required
                                        aria-label="Default select example">
                                        <option value="" selected>Select Partner</option>
                                        @foreach ($partners as $partner)
                                            <option value="{{ $partner->id }}"
                                                {{ $task->partner_id == $partner->id ? 'selected' : '' }}>
                                                {{ $partner->partner->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                        </div>

                        <!-- Task Name -->
                        <div class="row m-0">
                            <div class="col-xl-4">
                                <label for="">Task Name</label>
                                <input type="text" name="name" value="{{ $task->name }}" required
                                    placeholder="Task Name">
                            </div>
                            <!-- Date Required -->
                            <div class="col-xl-4">
                                <label for="">Date Required</label>
                                <div class="input-wrap">
                                    <input type="date" name="date_required" id="datepicker"
                                        value="{{ $task->date_required }}" required>
                                    <div class="form-group">
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <div> Urgent</div>
                                                <input type="checkbox" name="task_urgent"
                                                    {{ $task->task_urgent ? 'checked' : '' }} />
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Category and Asset -->
                        <div class="row m-0">
                            <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                <label for="">Category</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="" disabled>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $task->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                <label for="">Asset Type</label>
                                <select class="form-select" name="asset_id" required>
                                    <option value="" disabled>Select Asset</option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }}"
                                            {{ $task->asset_id == $asset->id ? 'selected' : '' }}>
                                            {{ $asset->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Size Dimensions -->
                        <div class="row m-0">
                            <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                <label for="">Width</label>
                                <input type="number" name="size_width" id="size_width" required
                                    value="{{ $task->size_width }}" placeholder="Size (Width)">
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                <label for="">Height</label>
                                <input type="number" name="size_height" id="size_height" required
                                    value="{{ $task->size_height }}" placeholder="Size (Height)">
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                <label for="">Measurement</label>
                                <input type="text" name="size_measurement" id="size_measurement" required
                                    value="{{ $task->size_measurement }}" placeholder="Size Measurement">
                            </div>
                        </div>
                        {{-- <div class="row m-0">
                            <div class="col-lg-6 col-xl-3">
                                <div class="input-wrap">
                                    <input type="text" name="size_width" id="size_width"
                                        value="{{ $task->size_width }}" required placeholder="Size (Width)">
                                    <input type="text" name="size_height" id="size_height"
                                        value="{{ $task->size_height }}" required placeholder="Size (Height)">
                                </div>
                            </div>
                        </div> --}}

                        <!-- Task Brief -->
                        <div class="row m-0">
                            <div class="col-md-12">
                                <label for="">Task Brief</label>
                                <textarea name="description" required id="editor" placeholder="Add a description for your Task">{{ $task->description }}</textarea>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-xl-4">
                                <div class="input-wrap">
                                    <label for="">Status</label>
                                    <div class="form-group">
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <div> Active</div>
                                                <input type="checkbox" name="is_active"
                                                    {{ $task->is_active ? 'checked' : '' }} />
                                                <span></span>

                                            </label>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="img-upload-con">
                            <div class="upload--col w-100">
                                <div class="drop-zone  update-campaign">
                                    <div class="drop-zone__prompt">
                                        <div class="drop-zone_color-txt">
                                            <span><img src="{{ $imageUrl }}" alt=""
                                                    class="main-image"></span>
                                            <br />
                                            <span><img src="{{ asset('assets/images/fi_upload-cloud.svg') }}"
                                                    alt=""> Upload
                                                Image</span>
                                        </div>

                                    </div>
                                    <!-- Existing Image Display -->
                                    {{-- @if ($imageUrl)
                                        <p>Existing Image:</p>
                                        <img src="{{ $imageUrl }}" alt="Existing Image" class="w-25">
                                    @endif --}}
                                    <input type="file" name="image" class="drop-zone__input">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                            {{-- <div class="sic-btn">
                                <a class="btn create-task" id="uploadAsset">Upload Assets</a>
                            </div> --}}
                            <div class="sic-btn">
                                <a class="btn link-asset" href="{{ route('tasks.index') }}" id="cancel">Cancel</a>
                            </div>
                            <div class="sic-btn">
                                <button class="btn download" id="save">Save</button>
                            </div>
                        </div>
                    </form>

                    <div id="modalLoader" class="modal-loader" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        // $(function() {
        //     $('#createTask').modal('toggle');
        // });
        function openModal() {
            $('#createTask').modal('show');
        }
    </script>

    <!-- jQuery and AJAX for Comment and Reply Submission -->

    <script>
        $(document).ready(function() {
            // Handle Comment Deletion via AJAX
            $(document).on('click', '.delete-comment', function() {
                const commentId = $(this).data('id');

                if (confirm('Are you sure you want to delete this comment?')) {
                    $.ajax({
                        url: `/comments/${commentId}`, // Adjust this URL according to your route
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // Include CSRF token for security
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove the comment from the DOM
                                $(`button[data-id="${commentId}"]`).closest('.col-md-12')
                                    .remove();
                            } else {
                                alert('Error deleting comment.');
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            alert('Error deleting comment.');
                        }
                    });
                }
            });

            // Handle Reply Deletion via AJAX
            $(document).on('click', '.delete-reply', function() {
                const replyId = $(this).data('id');
                const deleteCommentUrlTemplate =
                    "{{ route('comments.destroy', ['id' => ':id']) }}"; // Template route

                // Replace ':id' with the actual replyId
                const deleteCommentUrl = deleteCommentUrlTemplate.replace(':id', replyId);

                if (confirm('Are you sure you want to delete this reply?')) {
                    $.ajax({
                        url: deleteCommentUrl, // Use the dynamically built URL
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // Include CSRF token for security
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove the reply from the DOM
                                $(`button[data-id="${replyId}"]`).closest('.media').remove();
                                // alert(response.id)
                            } else {
                                alert('Error deleting reply.');
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            alert('Error deleting reply.');
                        }
                    });
                }
            });

        });


        $(document).ready(function() {

            // Handle Comment Submission via AJAX
            $('#commentForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: "{{ route('comments.store') }}", // Laravel route for storing comments
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response); // Log the full response
                        if (response.success) {
                            alert(
                                "Comment added successfully!"); // Check if this alert is shown
                            console.log("Response data:",
                                response); // Log response to see all fields

                            const userRoleLevel =
                                {{ Auth::user()->roles->first()->role_level }};

                            const newCommentHtml = `
            <div class="media mb-4 border-bottom pb-3">
                <img class="mr-3 rounded-circle" alt="User Profile Image"
                    src="{{ asset('/assets/images/profile-image.svg') }}"
                    style="width: 50px; height: 50px;" />
                <div class="media-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">${response.user.name}</h5>
                            <small class="text-muted">${response.created_at}</small>
                        </div>
                        <div>
                            <button class="btn btn-secondary btn-sm toggle-reply">
                                <i class="fas fa-reply"></i> Reply
                            </button>

                            
                           ${
                            userRoleLevel !== 3
                                    ? `<button class="btn btn-danger btn-sm delete-reply" data-id="${response.comment.id}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>`
                                    : ''
                            }
                        </div>
                    </div>
                    <p class="mt-2 comment-content">${response.comment.content}</p>
                </div>
            </div>`;

                            $('#comments-list').prepend(
                                newCommentHtml); // Add the new comment to the list
                            $('#commentForm')[0].reset(); // Clear the form
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Error adding comment.');
                    }
                });
            });



            // Event delegation for dynamically added reply forms
            // Event delegation for dynamically added reply forms
            $('#comments-list').on('submit', '.replyForm', function(e) {
                e.preventDefault(); // Prevent the form's default submission

                const $form = $(this); // Reference to the current form
                const commentId = $form.find('input[name="parent_id"]').val(); // Get the parent comment id

                $.ajax({
                    url: "{{ route('comments.store') }}", // Your Laravel route
                    method: 'POST',
                    data: $form.serialize(), // Serialize the form data
                    success: function(response) {
                        if (response.success && response.is_reply) {
                            console.log(response);
                            const userRoleLevel =
                                {{ Auth::user()->roles->first()->role_level }};

                            // Construct the new reply HTML
                            const newReplyHtml = `
                                    <div class="media mt-4">
                                        <img class="mr-3 rounded-circle" alt="User Profile Image"
                                            src="{{ asset('/assets/images/profile-image.svg') }}"
                                            style="width: 40px; height: 40px;" />
                                        <div class="media-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-0">${response.user.name}</h6>
                                                    <small class="text-muted">${response.created_at}</small>
                                                </div>
                                                <div>${
                                                userRoleLevel !== 3
                                                        ?
                                                    `<button class="btn btn-danger btn-sm delete-reply"
                                                                    data-id="${response.comment.id}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>`
                                                      : ''
                                                }
                                                </div>
                                            </div>
                                            <p class="mt-2">${response.comment.content}</p>
                                        </div>
                                    </div>`;

                            // Prepend the new reply to the correct comment (based on commentId)
                            $form.closest('.media-body').find('.reply-section').before(
                                newReplyHtml);

                            // Clear the reply form after successful submission
                            $form[0].reset();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // Log the error response
                        alert("Error adding reply.");
                    }
                });
            });


        });
    </script>
    <script>
        $(document).ready(function() {
            // Toggle reply section visibility
            $(document).on('click', '.toggle-reply', function() {
                const replySection = $(this).closest('.media-body').find('.reply-section');

                if (replySection.length) {
                    replySection.toggle(); // Show/hide the reply section
                } else {
                    console.error("Reply section not found!");
                }
            });

            // Toggle edit section visibility
            $('.toggle-edit').on('click', function() {
                $(this).closest('.media').find('.edit-section').slideToggle();
            });

            // Handle edit form submission (AJAX example)
            $('.editForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const commentId = form.find('input[name="comment_id"]').val();
                const updatedContent = form.find('input[name="updated_content"]').val();

                // AJAX request to update the comment
                $.ajax({
                    url: `/comments/${commentId}/edit`, // Adjust this route to match your application
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        content: updatedContent,
                    },
                    success: function(response) {
                        // Update the comment content on the page
                        form.closest('.media').find('.comment-content').text(updatedContent);

                        // Hide the edit section
                        form.closest('.edit-section').slideUp();

                        // Show success message (optional)
                        alert('Comment updated successfully!');
                    },
                    error: function() {
                        alert('Failed to update the comment. Please try again.');
                    },
                });
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
                clientName.value = '';

                if (campaignId) {
                    if (partnerDropdown) {
                        partnerDropdown.disabled = true; // Disable while fetching
                    }
                    fetch(`/get-partners-by-campaign/${campaignId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Populate Client Name
                            clientName.value = data.client?.name || 'No Client';
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
                            partnerDropdown.disabled = false;
                            $('#modalLoader').hide();
                        });
                }



            });
        });
    </script>
@endsection
