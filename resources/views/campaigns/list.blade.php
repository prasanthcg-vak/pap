@extends('layouts.app')

@section('content')
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <div class="task-ver">
                <div class="task-head heading_text">
                    <div class="title_status">
                        <h3>CAMPAIGN 5 - ASSETS</h3>
                        <p class="status green">Active</p>
                    </div>
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
                @foreach ($groupedAssets as $index => $assetsCollection)
                    <div class="list-view-acc">
                        <div class="accordion" id="accordionExample">
                            @php
                                $categoryName =
                                    $assetsCollection->first()->task->category->category_name ?? 'Uncategorized';
                                $taskName =
                                    $assetsCollection->first()->task->category->category_name ?? 'Uncategorized';
                            @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $loop->iteration }}">
                                    <button class="accordion-button @if ($loop->iteration-1 == 0) collapsed @endif"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $loop->iteration }}"
                                        aria-expanded="{{ $loop->iteration-1 == 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $loop->iteration }}">
                                        <span>{{ $categoryName }}</span>
                                        <i class="fa-solid fa-chevron-up"></i>
                                    </button>
                                    <div class="acc-number acc-numb-task">
                                        <p>3</p>
                                    </div>
                                </h2>
                                <div id="collapse{{ $loop->iteration }}"
                                    class="accordion-collapse collapse @if ($loop->iteration-1 == 0) show @endif"
                                    aria-labelledby="heading{{ $loop->iteration }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        @foreach ($assetsCollection as $image)
                                            <div class="list-item-task">
                                                <div class="list-item-head">
                                                    <div class="list-acc-title">
                                                        <p>{{ $image->task->name ?? 'No Name' }}</p>
                                                    </div>
                                                    <div class="list-acc-status">
                                                        <p>Status: {{ $image->task->task_status->name ?? 'Pending' }}</p>
                                                    </div>
                                                </div>
                                                <div class="list-item-con">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="list-item-img">
                                                                <img src="{{ Storage::disk('backblaze')->url($image->path) }}"
                                                                    alt="Asset Image">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="row align-items-center">
                                                                <div class="col-sm-6">
                                                                    <p class="profile-label">Category:</p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <p class="profile-data">{{ $categoryName }}</p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <p class="profile-label">Type:</p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <p class="profile-data">
                                                                        {{ $image->task->asset->type_name }}</p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <p class="profile-label">Size:</p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <p class="profile-data">{{ $image->task->size_width }}
                                                                        * {{ $image->task->size_height }} px</p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <p class="profile-label">Status:</p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <p class="profile-data">
                                                                        {{ $image->task->task_status->name }}</p>
                                                                </div>
                                                                <div class="col-12">
                                                                    <p class="profile-label"><i
                                                                            class="fa-solid fa-link"></i> Copy Direct Link
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <p class="profile-data">Send To:</p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="social-links">
                                                                        <ul>
                                                                            <li>
                                                                                <a href="">
                                                                                    <i class="fa-brands fa-twitter"></i>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="">
                                                                                    <i class="fa-brands fa-facebook"></i>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="">
                                                                                    <i class="fa-brands fa-instagram"></i>
                                                                                </a>
                                                                            </li>

                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-4">
                                                            <form action="{{ route('shared-assets.save') }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="profile-label">Share with Partners</p>
                                                                    </div>
                                                                    {{-- {{dd($image);}} --}}
                                                                    <div class="col-12">
                                                                        <div class="row partner-scroll">
                                                                            @foreach ($image->task->campaign->partner as $partner)
                                                                                <div
                                                                                    class="col-sm-6 d-flex align-items-center gap-2">
                                                                                    @php
                                                                                        $sharedPartnerIds = $image->sharedAssets
                                                                                            ->pluck('partner_id')
                                                                                            ->toArray();
                                                                                    @endphp
                                                                                    <input class="form-check-input mt-0"
                                                                                        type="checkbox" name="partners[]"
                                                                                        {{ in_array($partner->partner->id, $sharedPartnerIds) ? 'checked' : '' }}
                                                                                        value="{{ $partner->partner->id }}">
                                                                                    <p class="profile-data">
                                                                                        {{ $partner->partner->name }}</p>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="asset_id"
                                                                        value="{{ $image->id }}">
                                                                    <input type="hidden" name="task_id"
                                                                        value="{{ $image->task->id }}">
                                                                    @php
                                                                        // Check if sharedAssets exist and get the first entry's start_date and end_date
                                                                        $sharedAsset = $image->sharedAssets->first();
                                                                    @endphp

                                                                    <div class="col-sm-6">
                                                                        <p class="profile-label">Start Date:</p>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="date" id="start_date"
                                                                            name="start_date" class="date"
                                                                            value="{{ $sharedAsset ? \Carbon\Carbon::parse($sharedAsset->start_date)->format('Y-m-d') : '' }}"
                                                                            required>
                                                                    </div>

                                                                    <div class="col-sm-6">
                                                                        <p class="profile-label">End Date:</p>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="date" id="end_date" name="end_date"
                                                                            class="date"
                                                                            value="{{ $sharedAsset && $sharedAsset->end_date ? \Carbon\Carbon::parse($sharedAsset->end_date)->format('Y-m-d') : '' }}"
                                                                            >
                                                                    </div>


                                                                    <div class="col-12 mt-3">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Share
                                                                            Asset</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>


                                                    </div>
                                                    <div class="sic-action-btns d-flex justify-content-end flex-wrap">
                                                        <div class="sic-btn">
                                                            <button class="btn download">
                                                                Update
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
