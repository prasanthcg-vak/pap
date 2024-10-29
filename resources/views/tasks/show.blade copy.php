@extends('layouts.app')
{{-- <style>
    .status {
        float: right;
    }

    .status span {
        color: green;
    }

    .row {
        margin: 10px;
    }

    .float-right {
        float: right;
    }
</style> --}}
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                stroke="#EB8205" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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
                    <svg width="16" height="5" viewBox="0 0 16 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.00025 -0.000244141C9.0765 -0.000244141 10.0125 0.935756 10.0125 2.01201C10.0125 3.08826 9.07725 4.02426 8.00025 4.02426C6.924 4.02426 5.988 3.08901 5.988 2.01201C5.988 0.935006 6.924 -0.000244141 8.00025 -0.000244141ZM13.9882 -0.000244141C15.0645 -0.000244141 16.0005 0.935756 16.0005 2.01201C16.0005 3.08826 15.0652 4.02426 13.9882 4.02426C12.912 4.02426 11.976 3.08901 11.976 2.01201C11.976 0.935006 12.9112 -0.000244141 13.9882 -0.000244141ZM2.01225 -0.000244141C3.0885 -0.000244141 4.0245 0.935756 4.0245 2.01201C4.0245 3.08826 3.0885 4.02426 2.01225 4.02426C0.936 4.02426 0 3.08901 0 2.01201C0 0.935006 0.936 -0.000244141 2.01225 -0.000244141Z"
                            fill="#EB8205" />
                    </svg>

                </a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.66602 7.33351H10.7795L7.05302 3.60701L7.99952 2.66701L13.333 8.00051L7.99952 13.334L7.05952 12.394L10.7795 8.66751H2.66602V7.33351Z"
                        fill="#A1AEBE" />
                </svg>
            </li>
        </ol>
    </nav>
    <div class="campaign-card-contents task-table-info">
        <div class="col-lg-12 p-0">
            <div class="card">
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
                                        <p>{{ $task->size_width ?? 'N/A' }}(w) x {{ $task->size_height ?? 'N/A' }}(h) px</p>
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
                                            <button class="btn comment"><i class='bx bx-message-dots'></i></button>
                                            <button class="btn thumbs-up"><i class="fa-solid fa-thumbs-up"></i></button>
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
                                            <button class="btn comment"><i class='bx bx-message-dots'></i></button>
                                            <button class="btn thumbs-up"><i class="fa-solid fa-thumbs-up"></i></button>
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
                                            <button class="btn comment"><i class='bx bx-message-dots'></i></button>
                                            <button class="btn thumbs-up"><i class="fa-solid fa-thumbs-up"></i></button>
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
                                            <button class="btn comment"><i class='bx bx-message-dots'></i></button>
                                            <button class="btn thumbs-up"><i class="fa-solid fa-thumbs-up"></i></button>
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
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <div class="comments-header">
                            <div class="profile-fields">
                                <img src="{{ asset('public/') }}/assets/images/profile-image.svg" alt="profile-image">
                            </div>
                            <div class="comment-input-fields">
                                <input type="text" name="contents" placeholder="Add a comment" required>
                            </div>
                            <div class="comments-button">
                                <button type="submit" class="comments-btn">comment</button>
                            </div>



                            {{-- <button type="submit" class="btn btn-primary">Add Comment</button> --}}
                        </div>
                    </form>

                    <div class="mt-3 mb-3 container-fluid p-0">
                        <div class="card-contents">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        @foreach ($task->comments as $comment)
                                            <div class="col-md-12">
                                                <div class="media">
                                                    <img class="mr-3 rounded-circle" alt="User Profile Image"
                                                        src="{{ asset('public/') }}/assets/images/profile-image.svg" />
                                                    <div class="media-body">
                                                        <div class="row">
                                                            <div class="col-9 d-flex align-items-center">
                                                                <h5>{{ $comment->user->name }}</h5>
                                                                <span>- {{ $comment->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="pull-right reply">
                                                                    <a href="#"><span><i class="fa fa-reply"></i>
                                                                            reply</span></a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <p>{{ $comment->content }}</p>

                                                        <!-- Display replies -->
                                                        <form action="{{ route('comments.store') }}" method="POST"
                                                            class="mt-2">
                                                            @csrf
                                                            <input type="hidden" name="task_id"
                                                                value="{{ $task->id }}">
                                                            <input type="hidden" name="parent_id"
                                                                value="{{ $comment->id }}">

                                                            {{-- <button type="submit"
                                                                class="btn btn-secondary btn-sm">Reply</button> --}}

                                                            <div class="comment-input-fields">
                                                                <input type="text" name="contents"
                                                                    placeholder="Add a reply" required>
                                                            </div>
                                                            <div class="comments-button">
                                                                <button type="submit" class="comments-btn">Reply</button>
                                                            </div>
                                                        </form>
                                                        @if ($comment->replies->count())
                                                            @foreach ($comment->replies as $reply)
                                                                <div class="media mt-4">
                                                                    <a class="pr-3" href="#">
                                                                        <img class="rounded-circle"
                                                                            alt="User Profile Image"
                                                                            src="{{ asset('public/') }}/assets/images/profile-image.svg" />
                                                                    </a>
                                                                    <div class="media-body">
                                                                        <div class="row">
                                                                            <div class="col-12 d-flex align-items-center ">
                                                                                <h5>{{ $reply->user->name }}</h5>
                                                                                <span> -
                                                                                    {{ $reply->created_at->diffForHumans() }}</span>
                                                                            </div>
                                                                        </div>
                                                                        <p>{{ $reply->content }}</p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                        <!-- Reply form -->

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
                        <a href="#" class="btn edit-task-btn" data-toggle="modal" data-target="#createTask">Edit Task</a>
                        <a href="#" class="btn complete-task-btn">Complete Task</a>
                    </div>
                </div>
                <!-- campaign task cost -->
            </div>
        </div>
    </div>
    <div class="container">
        <h1>View Task Details</h1>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="campaign_id">Campaign</label>
                    <div class="form-control">
                        {{ $task->campaign->name ?? 'N/A' }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="status">
                        <label for="task_status"><b>Status :</b> <span>{{ $task->status['name'] ?? 'N/A' }}</span></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Task Name</label>
                    <div class="form-control">
                        {{ $task->name ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_required">Date Required</label>
                    <div class="form-control">
                        {{ $task->date_required ?? 'N/A' }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="task_urgent">Urgent</label>
                    <div class="form-control">
                        {{ $task->task_urgent ? 'Yes' : 'No' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="asset_type">Asset Type</label>
                    <div class="form-control">
                        {{ $task->asset_type ?? 'N/A' }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <div class="form-control">
                        {{ $task->category->category_name ?? 'N/A' }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="size_width">Size (Width)</label>
                    <div class="form-control">
                        {{ $task->size_width ?? 'N/A' }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="size_height">Size (Height)</label>
                    <div class="form-control">
                        {{ $task->size_height ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label for="description">Task Brief</label>
                <div class="form-control" style="height: 250px; overflow-y: auto;">
                    {{ $task->description ?? 'N/A' }}
                </div>
            </div>
        </div>
        <div class="row">

            <label for="description">Uploads</label>
            <div class="col-3">
                <a href="">file1.pdf</a>
            </div>
            <div class="col-3">
                <a href="">file2.jpg</a>
            </div>
            <div class="col-3">
                <a href="">file3.mp3</a>
            </div>
            <div class="col-3">
                <a href="">file4.mp4</a>
            </div>

        </div>
        <hr>
        <div class="container">
            <h2>Comments for Task: {{ $task->name }}</h2>

            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task->id }}">
                <div class="form-group">
                    <textarea name="contents" class="form-control" rows="3" placeholder="Add a comment..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Comment</button>
            </form>

            <hr>

            @foreach ($task->comments as $comment)
                <div class="card mt-3">
                    <div class="card-body">
                        <p>{{ $comment->content }}</p>

                        <!-- Reply form -->
                        <form action="{{ route('comments.store') }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <div class="form-group">
                                <textarea name="contents" class="form-control" rows="2" placeholder="Reply..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm">Reply</button>
                        </form>

                        <!-- Display replies -->
                        @if ($comment->replies->count())
                            <div class="ml-4 mt-2">
                                @foreach ($comment->replies as $reply)
                                    <div class="card mt-2">
                                        <div class="card-body">
                                            <p>{{ $reply->content }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="form-group">
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Edit Task</a>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
    <a href="#" class="create-task-btn" data-toggle="modal" data-target="#createTask">Create Task</a>

    <div class="modal fade editTask-modal" id="createTask" tabindex="-1" aria-labelledby="editTaskLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editTaskLabel">Edit Task</h1>
                    <p class="status green">Active</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Use PUT for update requests -->
    
                        <div class="row m-0">
                            <div class="col-xl-4">
                                <select class="form-select" name="campaign_id" required aria-label="Default select example">
                                    <option>Select Campaign</option>
                                    @foreach ($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}" {{ $task->campaign_id == $campaign->id ? 'selected' : '' }}>
                                            {{ $campaign->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-xl-4">
                                <input type="text" name="name" required placeholder="Task Name" value="{{ $task->name }}">
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-xl-4">
                                <label for="">Date Required</label>
                                <div class="input-wrap">
                                    <input type="text" name="date_required" id="datepicker" required placeholder="Date Required" value="{{ $task->date_required }}">
    
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
                        <div class="row m-0">
                            <div class="col-lg-6 col-xl-4 mb-4 mb-lg-0">
                                <select class="form-select" name="category" required aria-label="Default select example">
                                    <option>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $task->category == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-xl-3 p-xl-0">
                                <div class="input-wrap">
                                    <input type="text" name="size_width" id="size_width" required placeholder="Size (Width)" value="{{ $task->size_width }}">
                                    <input type="text" name="size_height" id="size_height" required placeholder="Size (Height)" value="{{ $task->size_height }}">
                                </div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-md-12">
                                <label for="">Task Brief</label>
                                <textarea name="description" required id="description">{{ $task->description }}</textarea>
                            </div>
                            <span class="info-text">Add a description for your Task</span>
                        </div>
                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                            <div class="sic-btn">
                                <button class="btn create-task" id="uploadAsset">
                                    Upload Assets
                                </button>
                            </div>
                            <div class="sic-btn">
                                <button class="btn link-asset" id="cancel" data-bs-dismiss="modal" aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                            <div class="sic-btn">
                                <button class="btn download" id="save">
                                    Save
                                </button>
                            </div>
                        </div>
    
                        <div class="img-upload-con d-none">
                            <div class="upload--col">
                                <div class="drop-zone">
                                    <div class="drop-zone__prompt">
                                        <div class="drop-zone_color-txt">
                                            <span><img src="assets/images/Image.png" alt=""></span> <br />
                                            <span><img src="assets/images/fi_upload-cloud.svg" alt=""> Upload Image</span>
                                        </div>
                                        <div class="file-format">
                                            <p>Upload a cover image for your product.</p>
                                            <p>File Format <b> jpeg, png</b>. Recommended Size <b>600x600 (1:1)</b></p>
                                        </div>
                                    </div>
                                    <input type="file" name="myFile" class="drop-zone__input">
                                </div>
                            </div>
                            <div class="additional-img">
                                <label for="">Additional Images</label>
    
                                <div class="upload--col">
                                    <div class="drop-zone">
                                        <div class="drop-zone__prompt">
                                            <div class="drop-zone_color-txt">
                                                <span><img src="assets/images/Image.png" alt=""></span> <br />
                                                <span><img src="assets/images/fi_upload-cloud.svg" alt=""> Upload Image</span>
                                            </div>
                                        </div>
                                        <input type="file" name="myFile" class="drop-zone__input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
