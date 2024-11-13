@extends('layouts.app')

@section('content')

<div class="CM-main-content">
    <div class="container-fluid p-0">
        <!--Single Image Container-->
        <div class="single-img-con">
            <div class="sic-wrap">
                <div class="sic-header d-flex justify-content-between align-items-center">
                    <h3>ASSET #01</h3>
                    <p class="status green">Active</p>
                </div>

                <div class="sic-img-info">
                    <ul
                        class="list-unstyled p-0 m-0 d-flex flex-column flex-md-row align-items-md-center flex-wrap">
                        <li>
                            <span>Type: {{$fileExtension}}</span>
                        </li>
                        <li> <span>Dimensions: 1200px (w) 628px (h)</span></li>
                        <li> <span> Size: {{$fileSizeKB}}kb</span></li>
                    </ul>
                </div>

                <div class="sic-src-wrap">
                    <img  class="w-50" src="{{$image_path}}" alt="">
                </div>

                <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                    <div class="sic-btn">
                        <button class="btn create-task" data-bs-toggle="modal" data-bs-target="#createTask">
                            create task
                        </button>
                    </div>
                    <div class="sic-btn">
                        <button class="btn link-asset" data-bs-toggle="modal" data-bs-target="#linkAsset">
                            link asset
                        </button>
                    </div>
                    <div class="sic-btn">
                        <button class="btn download">
                            download
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--End Single Image Container-->
    </div>
</div>
<div class="modal fade createTask-modal" id="createTask" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Task
                    </h1>
                    <p class="status green">Active</p>
                    <span class="btn-close" data-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <div class="row m-0">
                            <div class="col-xl-4">
                                <select class="form-select" name="campaign_id" required aria-label="Default select example">
                                    {{-- <option selected>Select Campaign</option> --}}
                                    {{-- @foreach ($campaigns as $campaign) --}}
                                        <option value="{{ $campaigns->id }}">{{ $campaigns->name }}</option>
                                    {{-- @endforeach --}}
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

                                <select class="form-select" name="category1" disabled aria-label="Default select example">
                                    <option selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }} ">
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                               
                                
                                <!-- Hidden input to actually submit the selected category value -->
                                <input type="hidden" name="category" value="{{ $category->id }}">
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
                                <button type="button" class="btn thumbs-up " data-bs-dismiss="modal">Cancel</button>
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
   

@endsection