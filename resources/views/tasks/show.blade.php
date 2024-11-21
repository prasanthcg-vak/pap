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
                                <span>Campaign 5</span>
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
                            <span>Task #01 - Name of Task</span>
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
                            <div class="title_status">
                                <h3> {{ strtoupper($task->name ?? 'N/A') }}</h3>
                                <p class="status green">{{ $task->status['name'] ?? 'N/A' }}</p>
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
                                                <p>{{ $task->asset_type ?? 'N/A' }}</p>
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
                                                    {{ $task->size_height ?? 'N/A' }}(h) px</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="Task-brief-fields">
                                        <label>Task Brief:</label>
                                        <p>{{ $task->description ?? 'N/A' }}</p>
                                    </div>
                                    <div class="upload-contents">
                                        <label>Uploads:</label>
                                        <div class="upload-content-links">
                                            <a href="#">Stockimage01.jpg</a>
                                            <a href="#">Worddocument.doc</a>
                                            <a href="#">example.pdf</a>
                                        </div>
                                    </div>
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
                                            <td class="library-img">
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
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- Table -->
                        <!-- Comments -->
                        <div class="comments-section">
                            <div class="title_status mt-3">
                                <h3>COMMENTS</h3>
                            </div>

                            <!-- Comment Form -->
                            <form id="commentForm" method="POST">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                <div class="comments-header">
                                    <div class="profile-fields">
                                        <img src="{{ asset('/assets/images/profile-image.svg') }}" alt="profile-image">
                                    </div>
                                    <div class="comment-input-fields">
                                        <input type="text" name="contents" placeholder="Add a comment" required>
                                    </div>
                                    <div class="comments-button">
                                        <button type="submit" class="comments-btn">comment</button>
                                    </div>
                                </div>
                            </form>

                            <!-- Comments Display Section -->
                            <div id="comments-list" class="mt-3 mb-3 container-fluid p-0">
                                <div class="card card-shadow p-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @foreach ($task->comments->reverse() as $comment)
                                                    <div class="media mb-4 border-bottom pb-3">
                                                        <img class="mr-3 rounded-circle" alt="User Profile Image"
                                                            src="{{ asset('/assets/images/profile-image.svg') }}"
                                                            style="width: 50px; height: 50px;" />
                                                        <div class="media-body">
                                                            <div class="d-flex justify-content-between">
                                                                <div>
                                                                    <h5 class="mb-0">{{ $comment->user->name }}</h5>
                                                                    <small
                                                                        class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                                </div>
                                                                <div>
                                                                    <button class="btn btn-secondary btn-sm toggle-reply">
                                                                        <i class="fas fa-reply"></i> Reply
                                                                    </button>
                                                                    <button class="btn btn-warning btn-sm toggle-edit">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </button>
                                                                    <button class="btn btn-danger btn-sm delete-reply"
                                                                        data-id="{{ $comment->id }}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <p class="mt-2 comment-content">{{ $comment->content }}</p>

                                                            <!-- Reply Form (Initially Hidden) -->
                                                            <div class="reply-section mt-3" style="display: none;">
                                                                <form class="replyForm d-flex align-items-center"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="task_id"
                                                                        value="{{ $task->id }}">
                                                                    <input type="hidden" name="parent_id"
                                                                        value="{{ $comment->id }}">
                                                                    <input type="text" class="form-control me-2"
                                                                        name="contents" placeholder="Add a reply"
                                                                        required>
                                                                    <button type="submit" class="btn btn-primary">
                                                                        <i class="fas fa-paper-plane"></i>
                                                                    </button>
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
                                                                    <button type="submit" class="btn btn-success">
                                                                        <i class="fas fa-save"></i> Save
                                                                    </button>
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
                                <a href="#" class="btn edit-task-btn" data-toggle="modal"
                                    data-target="#createTask">Edit
                                    Task</a>
                                <a href="#" class="btn complete-task-btn">Complete Task</a>
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
                    <span class="btn-close" data-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- HTTP method for updating -->
                        
                        <!-- Campaign and Partner -->
                        <div class="row m-0">
                            <div class="col-xl-4">
                                <select class="form-select" id="campaign-select" name="campaign_id" required>
                                    <option value="" disabled>Select Campaign</option>
                                    @foreach ($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}" {{ $task->campaign_id == $campaign->id ? 'selected' : '' }}>
                                            {{ $campaign->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4">
                                <select class="form-select" id="partner-select" name="partner_id" required>
                                    <option value="">Select Partner</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->partner->id }}" {{ $task->partner_id == $partner->partner->id ? 'selected' : '' }}>
                                            {{ $partner->partner->name ?? 'Unnamed Partner' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    
                        <!-- Task Name -->
                        <div class="row m-0">
                            <div class="col-xl-4">
                                <input type="text" name="name" value="{{ $task->name }}" required placeholder="Task Name">
                            </div>
                        </div>
                    
                        <!-- Date Required -->
                        <div class="row m-0">
                            <div class="col-xl-4">
                                <label for="">Date Required</label>
                                <div class="input-wrap">
                                    <input type="date" name="date_required" id="datepicker" value="{{ $task->date_required }}" required>
                                    <div class="form-group">
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <div> Urgent</div>
                                                <input type="checkbox" name="task_urgent" {{ $task->task_urgent ? 'checked' : '' }} />
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Category and Asset -->
                        <div class="row m-0">
                            <div class="col-lg-6 col-xl-4">
                                <select class="form-select" name="category_id" required>
                                    <option value="" disabled>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <select class="form-select" name="asset_id" required>
                                    <option value="" disabled>Select Asset</option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }}" {{ $task->asset_id == $asset->id ? 'selected' : '' }}>
                                            {{ $asset->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    
                        <!-- Size Dimensions -->
                        <div class="row m-0">
                            <div class="col-lg-6 col-xl-3">
                                <div class="input-wrap">
                                    <input type="text" name="size_width" id="size_width" value="{{ $task->size_width }}" required
                                        placeholder="Size (Width)">
                                    <input type="text" name="size_height" id="size_height" value="{{ $task->size_height }}" required
                                        placeholder="Size (Height)">
                                </div>
                            </div>
                        </div>
                    
                        <!-- Task Brief -->
                        <div class="row m-0">
                            <div class="col-md-12">
                                <label for="">Task Brief</label>
                                <textarea name="description" required id="description" placeholder="Add a description for your Task">{{ $task->description }}</textarea>
                            </div>
                        </div>
                    
                        <!-- Image Upload -->
                        <div class="img-upload-con">
                            <div class="upload--col w-100">
                                <div class="drop-zone">
                                    <div class="drop-zone__prompt">
                                        <div class="drop-zone_color-txt">
                                            <span><img src="{{$imageUrl}}" alt="" class="w-50"></span> <br />
                                            <span><img src="{{asset('assets/images/fi_upload-cloud.svg')}}" alt=""> Upload
                                                Image</span>
                                        </div>
                                        <div class="file-format">
                                            <p>Upload a cover image for your product.</p>
                                            <p>File Format <b>jpeg, png</b>. Recommended Size <b>600x600 (1:1)</b></p>
                                        </div>
                                    </div>
                                    <!-- Existing Image Display -->
                                    {{-- @if($imageUrl)
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
                    
                    
                </div>
            </div>
        </div>
    </div>


    <script>
        $(function() {
            $('#createTask').modal('toggle');
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                                alert(response.id)
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
                e.preventDefault(); // Prevent form from submitting traditionally

                $.ajax({
                    url: "{{ route('comments.store') }}", // Your Laravel route
                    method: 'POST',
                    data: $(this).serialize(), // Serialize the form data
                    success: function(response) {
                        if (response.success) {
                            // Append the new comment dynamically
                            $('#comments-list .comment-media').prepend(`
                        <div class="col-md-12">
                            <div class="media">
                                <img class="mr-3 rounded-circle" alt="User Profile Image"
                                    src="{{ asset('/assets/images/profile-image.svg') }}" />
                                <div class="media-body">
                                    <div class="row">
                                        <div class="col-9 d-flex align-items-center">
                                            <h5>${response.user.name}</h5>
                                            <span>- ${response.created_at} <button class="btn btn-danger btn-sm delete-reply m-2" data-id="${response.comment.id}"><i class="fas fa-trash"></i></button></span>
                                        </div>
                                        <div class="col-4">
                                            <div class="pull-right reply">
                                                <a href="#"><span><i class="fa fa-reply"></i> reply</span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <p>${response.comment.content}</p>
                                    <!-- Reply Form -->
                                    <form class="replyForm mt-2 d-flex" method="POST">
                                        @csrf
                                        <input type="hidden" name="task_id" value="${response.comment.tasks_id}">
                                        <input type="hidden" name="parent_id" value="${response.comment.id}">
                                        <div class="comment-input-fields">
                                            <input type="text" name="contents" placeholder="Add a reply" required>
                                        </div>
                                        <div class="comments-button">
                                            <button type="submit" class="comments-btn"><i class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `);
                            // Clear the comment input field after successful submission
                            $('#commentForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert("Error adding comment.");
                    }
                });
            });

            // Event delegation for dynamically added reply forms
            $('#comments-list').on('submit', '.replyForm', function(e) {
                e.preventDefault(); // Prevent form from submitting traditionally

                $.ajax({
                    url: "{{ route('comments.store') }}", // Same route for replies
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success && response.is_reply) {
                            // Append the new reply dynamically
                            $(this).closest('.media-body').append(`
                        <div class="media mt-4">
                            <a class="pr-3" href="#">
                                <img class="rounded-circle" alt="User Profile Image"
                                    src="{{ asset('/assets/images/profile-image.svg') }}" />
                            </a>
                            <div class="media-body">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center">
                                        <h5>${response.user.name}</h5>
                                        <span> - ${response.created_at}</span>
                                    </div>
                                </div>
                                <p>${response.comment.content}</p>
                            </div>
                        </div>
                    `);
                            // Clear the reply input field after successful submission
                            $(this)[0].reset();
                        }
                    }.bind(this), // Ensure 'this' context refers to the form
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert("Error adding reply.");
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Toggle reply section visibility
            $('.toggle-reply').on('click', function() {
                $(this).closest('.media').find('.reply-section').slideToggle();
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
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
