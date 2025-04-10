@extends('layouts.app')

@section('content')
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!-- Table -->
            <div class="task campaingn-table pb-3 common-table">
                <!-- campaigns-contents -->
                <div class="col-lg-12 task campaigns-contents">
                    <div class="campaigns-title">
                        <h3>LIBRARY</h3>
                    </div>

                </div>
                @foreach ($groupedAssets as $index => $assetsCollection)
                    <div class="list-view-acc">
                        <div class="accordion" id="accordionExample">
                            @php
                                $categoryName = $assetsCollection->first()->task->category->category_name ?? $index;
                                $taskName =
                                    $assetsCollection->first()->task->category->category_name ?? 'Uncategorized';
                            @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $loop->iteration }}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="false"
                                        aria-controls="collapse{{ $loop->iteration }}">
                                        <p>{{ $categoryName }}</p>
                                        <div class="acc-arrow">
                                            <i class="fa-solid fa-chevron-up"></i>
                                        </div>
                                    </button>
                                    <div class="acc-right-con">
                                        <div class="layout-view d-inline-flex">
                                            <div class=" layout-btn list active">
                                                <i class="box-icon bx bx-list-ul"></i>
                                            </div>
                                            <div class=" layout-btn table">
                                                <i class="fa-solid fa-table"></i>
                                            </div>
                                            <div class="layout-btn grid">
                                                <i class='box-icon bx bxs-grid-alt'></i>
                                            </div>
                                        </div>
                                        <div class="acc-number">
                                            <p>{{ $assetsCollection->count() }}</p>
                                        </div>
                                    </div>
                                </h2>
                                <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $loop->iteration }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="acc-view">
                                            <div class="acc-list-view">
                                                @foreach ($assetsCollection as $image)
                                                    @php
                                                        $imageSharedAssetsExist =
                                                            count(
                                                                $image->sharedAssets->pluck('partner_id')->toArray(),
                                                            ) > 0;
                                                    @endphp
                                                    <div class="list-item-task">
                                                        <div class="list-item-head">
                                                            <div class="list-acc-title">
                                                                <p>{{ $image->task->name ?? 'No Name' }}</p>
                                                            </div>
                                                            <div class="list-acc-status">
                                                                <p>Status:
                                                                    @if ($imageSharedAssetsExist)
                                                                        Shared
                                                                        <div class="crew-mark tick">
                                                                            <svg width="14" height="12"
                                                                                viewBox="0 0 14 12" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M1.75259 5.82709C1.38891 5.37366 0.757146 5.32772 0.34151 5.72446C-0.0741262 6.12121 -0.116244 6.8104 0.247437 7.26382L3.74743 11.6275C4.13583 12.1117 4.82204 12.1259 5.22702 11.6581L13.727 1.83995C14.1062 1.40194 14.0881 0.711492 13.6866 0.297807C13.2851 -0.115879 12.6522 -0.0961519 12.273 0.341868L4.52826 9.28766L1.75259 5.82709Z"
                                                                                    fill="black"></path>
                                                                            </svg>
                                                                        </div>
                                                                    @else
                                                                        Not Shared
                                                                        <div class="crew-mark minus">
                                                                            <i class="fa-solid fa-exclamation"></i>
                                                                        </div>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="list-item-con">
                                                            <div class="row">
                                                                <div class="col-lg-4">
                                                                    <div class="list-item-img">
                                                                        {{-- {{ dd($image->skip(1)->first()); }} --}}

                                                                            @if($image['file_type'] == 'image')
                                                                            <img src="{{ Storage::disk('backblaze')->url($image->path) }}" alt="Post Image">
                                                                            @elseif($image['file_type'] == 'video')
                                                                            <video controls width="600">
                                                                                <source src="{{ Storage::disk('backblaze')->url($image->path) }}" type="video/mp4">
                                                                                Your browser does not support the video tag.
                                                                            </video>
                                                                            @else
                                                                            <img src="{{ Storage::disk('backblaze')->url($image->thumbnail_path) }}"
                                                                            alt="Asset Image">
                                                                                <a href="{{ Storage::disk('backblaze')->url($image->path) }}" target="_blank" rel="noopener">Download File</a>
                                                                            @endif
                                                                    </div>
                                                                </div>
                                                                @php
                                                                    $fileSize = Storage::disk('backblaze')->size(
                                                                        $image->path,
                                                                    );
                                                                    $fileSizeKB = round($fileSize / 1024, 2);
                                                                @endphp
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
                                                                            <p class="profile-label">Dimension:</p>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <p class="profile-data">
                                                                                {{ $image->task->size_width }}
                                                                                X
                                                                                {{ $image->task->size_height }}{{ $image->task->size_measurement }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <p class="profile-label">Size:</p>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <p class="profile-data">
                                                                                {{ $fileSizeKB }} KB</p>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <p class="profile-label">Status:</p>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <p class="profile-data">

                                                                                {{ $image->task?->task_status?->name ?? 'N/A' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <p class="profile-label"><i
                                                                                    class="fa-solid fa-link"></i> Copy
                                                                                Direct Link
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <p class="profile-data">Send To:</p>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="social-links">
                                                                                <ul>
                                                                                    <li><a href="{{ $image->social_links['linkedin'] }}"
                                                                                            id="linkedinShare"
                                                                                            target="_blank"
                                                                                            aria-label="Share on LinkedIn"><i
                                                                                                class="fa-brands fa-linkedin"></i></a>
                                                                                    </li>
                                                                                    <li><a href="{{ $image->social_links['facebook'] }}"
                                                                                            id="facebookShare"
                                                                                            target="_blank"
                                                                                            aria-label="Share on Facebook"><i
                                                                                                class="fa-brands fa-facebook"></i></a>
                                                                                    </li>
                                                                                    <li><a href="{{ $image->social_links['twitter'] }}"
                                                                                            id="twitterShare"
                                                                                            target="_blank"
                                                                                            aria-label="Share on Twitter"><i
                                                                                                class="fa-brands fa-x-twitter"></i></a>
                                                                                    </li>
                                                                                    <li><a href="{{ $image->social_links['reddit'] }}"
                                                                                            id="redditShare"
                                                                                            target="_blank"
                                                                                            aria-label="Share on Reddit"><i
                                                                                                class="fa-brands fa-reddit-alien"></i></a>
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
                                                                                <p class="profile-label">Share with
                                                                                    Partners
                                                                                </p>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="row partner-scroll">
                                                                                    @foreach ($image->task->campaign->partner as $partner)
                                                                                        <div
                                                                                            class="col-sm-6 d-flex align-items-center gap-2">
                                                                                            @php
                                                                                                $sharedPartnerIds = $image->sharedAssets
                                                                                                    ->pluck(
                                                                                                        'partner_id',
                                                                                                    )
                                                                                                    ->toArray();
                                                                                            @endphp
                                                                                            <input
                                                                                                class="form-check-input mt-0"
                                                                                                type="checkbox"
                                                                                                name="partners[]"
                                                                                                {{ in_array($partner->partner->id, $sharedPartnerIds) ? 'checked' : '' }}
                                                                                                value="{{ $partner->partner->id }}"
                                                                                                {{ $imageSharedAssetsExist ? 'disabled' : '' }}>
                                                                                            <p class="profile-data">
                                                                                                {{ $partner->partner->name }}
                                                                                            </p>
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
                                                                                    {{ $imageSharedAssetsExist ? 'readonly' : '' }}>
                                                                            </div>

                                                                            <div class="col-sm-6">
                                                                                <p class="profile-label">End Date:</p>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <input type="date" id="end_date"
                                                                                    name="end_date" class="date"
                                                                                    value="{{ $sharedAsset && $sharedAsset->end_date ? \Carbon\Carbon::parse($sharedAsset->end_date)->format('Y-m-d') : '' }}">
                                                                            </div>


                                                                            <div
                                                                                class="col-12 mt-3 sic-action-btns d-flex justify-content-end flex-wrap">
                                                                                <div class="sic-btn">
                                                                                    @if ($imageSharedAssetsExist)
                                                                                        <a class="btn create-task view-asset"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#asset-view-modal"
                                                                                            data-task-id="{{ $image->task->id }}"
                                                                                            data-image-id="{{ $image->id }}"
                                                                                            data-name="{{ $image->task->name ?? 'No Name' }}"
                                                                                            data-campaign= "{{ $image->task->campaign->name }}"
                                                                                            data-status="{{ $imageSharedAssetsExist ? 'Shared' : 'Not Shared' }}"
                                                                                            data-category="{{ $categoryName }}"
                                                                                            data-type="{{ $image->task->asset->type_name }}"
                                                                                            data-size="{{ $image->task->size_width }}x{{ $image->task->size_height }} {{ $image->task->size_measurement }}"
                                                                                            data-start-date="{{ $sharedAsset ? \Carbon\Carbon::parse($sharedAsset->start_date)->format('Y-m-d') : '' }}"
                                                                                            data-end-date="{{ $sharedAsset && $sharedAsset->end_date ? \Carbon\Carbon::parse($sharedAsset->end_date)->format('Y-m-d') : '' }}"
                                                                                            data-image="{{ Storage::disk('backblaze')->url($image->path) }}"
                                                                                            data-partners="{{ json_encode($image->task->campaign->partner) }}"
                                                                                            data-shared-assets="{{ json_encode($image->sharedAssets->pluck('partner_id')->toArray()) }}"
                                                                                            data-image-shared-assets="{{ $imageSharedAssetsExist }}"
                                                                                            data-link="www.website.com/link-to-asset"
                                                                                            data-notes={{ $image->additional_notes }}>
                                                                                            View
                                                                                        </a>
                                                                                    @else
                                                                                        <a class="btn create-task edit-asset"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#asset-edit-modal"
                                                                                            data-task-id="{{ $image->task->id }}"
                                                                                            data-image-id="{{ $image->id }}"
                                                                                            data-name="{{ $image->task->name ?? 'No Name' }}"
                                                                                            data-campaign= "{{ $image->task->campaign->name }}"
                                                                                            data-status="{{ $imageSharedAssetsExist ? 'Shared' : 'Not Shared' }}"
                                                                                            data-category="{{ $categoryName }}"
                                                                                            data-type="{{ $image->task->asset->type_name }}"
                                                                                            data-size="{{ $image->task->size_width }}x{{ $image->task->size_height }} {{ $image->task->size_measurement }}"
                                                                                            data-image="{{ Storage::disk('backblaze')->url($image->path) }}"
                                                                                            data-partners="{{ json_encode($image->task->campaign->partner) }}"
                                                                                            data-link="www.website.com/link-to-asset"
                                                                                            data-notes="Additional public facing notes go here">
                                                                                            Edit
                                                                                        </a>
                                                                                    @endif


                                                                                    <button
                                                                                        class="btn download">Update</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>


                                                            </div>

                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                            <div class="acc-table-view common-table">
                                                <div class="table-wrapper">
                                                    <table class="list-view">
                                                        <thead>
                                                            <tr>
                                                                <th class="">
                                                                    <span>Asset Name</span>
                                                                </th>
                                                                <th>
                                                                    <span>Campaign</span>
                                                                </th>
                                                                <th>
                                                                    <span>Type</span>
                                                                </th>

                                                                <th class="">
                                                                    <span>Size</span>
                                                                </th>
                                                                <th class="">
                                                                    <span>End Date</span>
                                                                </th>
                                                                <th class="status">
                                                                    <span>status</span>
                                                                </th>
                                                                <th class="">
                                                                    <span>action</span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($assetsCollection as $image)
                                                                <tr>
                                                                    <td class="campaingn-title">
                                                                        <span>{{ $image->task->name }} </span>
                                                                    </td>
                                                                    <td class="library-camp-title">
                                                                        <span>Campaign 5</span>
                                                                    </td>
                                                                    <td class="library-file-name">
                                                                        <span>Web Banner</span>
                                                                    </td>

                                                                    <td class="library-file-size">
                                                                        <span>1200*600px
                                                                        </span>
                                                                    </td>
                                                                    <td class="library-file-date">
                                                                        <span>12/01/2025
                                                                        </span>
                                                                    </td>
                                                                    <td class="library-status">
                                                                        {{-- {{ $image->task->task_status->name ?? 'Pending' }} --}}
                                                                        @if ($imageSharedAssetsExist)
                                                                            <div class="crew-mark tick">
                                                                                <svg width="14" height="12"
                                                                                    viewBox="0 0 14 12" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M1.75259 5.82709C1.38891 5.37366 0.757146 5.32772 0.34151 5.72446C-0.0741262 6.12121 -0.116244 6.8104 0.247437 7.26382L3.74743 11.6275C4.13583 12.1117 4.82204 12.1259 5.22702 11.6581L13.727 1.83995C14.1062 1.40194 14.0881 0.711492 13.6866 0.297807C13.2851 -0.115879 12.6522 -0.0961519 12.273 0.341868L4.52826 9.28766L1.75259 5.82709Z"
                                                                                        fill="black"></path>
                                                                                </svg>
                                                                            </div>
                                                                        @else
                                                                            <div class="crew-mark minus">
                                                                                <i class="fa-solid fa-exclamation"></i>
                                                                            </div>
                                                                        @endif


                                                                    </td>
                                                                    <td class="library-action action-btn-icons">
                                                                        <button
                                                                            {{ $imageSharedAssetsExist ? '' : 'disabled' }}
                                                                            class="btn search" data-bs-toggle="modal"
                                                                            data-bs-target="#asset-view-modal"
                                                                            data-task-id="{{ $image->task->id }}"
                                                                            data-image-id="{{ $image->id }}"
                                                                            data-name="{{ $image->task->name ?? 'No Name' }}"
                                                                            data-campaign= "{{ $image->task->campaign->name }}"
                                                                            data-status="{{ $imageSharedAssetsExist ? 'Shared' : 'Not Shared' }}"
                                                                            data-category="{{ $categoryName }}"
                                                                            data-type="{{ $image->task->asset->type_name }}"
                                                                            data-size="{{ $image->task->size_width }}x{{ $image->task->size_height }} {{ $image->task->size_measurement }}"
                                                                            data-start-date="{{ $sharedAsset ? \Carbon\Carbon::parse($sharedAsset->start_date)->format('Y-m-d') : '' }}"
                                                                            data-end-date="{{ $sharedAsset && $sharedAsset->end_date ? \Carbon\Carbon::parse($sharedAsset->end_date)->format('Y-m-d') : '' }}"
                                                                            data-image="{{ Storage::disk('backblaze')->url($image->path) }}"
                                                                            data-partners="{{ json_encode($image->task->campaign->partner) }}"
                                                                            data-shared-assets="{{ json_encode($image->sharedAssets->pluck('partner_id')->toArray()) }}"
                                                                            data-image-shared-assets="{{ $imageSharedAssetsExist }}"
                                                                            data-link="www.website.com/link-to-asset"
                                                                            data-notes={{ $image->additional_notes }}><i
                                                                                class="bx bx-search-alt-2"></i></button>
                                                                        <button class="btn new-link"
                                                                            onclick="openlinkmodel(this)"
                                                                            data-url="{{ $image->post_url }}"
                                                                            data-linkedin="{{ $image->social_links['linkedin'] }}"
                                                                            data-facebook="{{ $image->social_links['facebook'] }}"
                                                                            data-twitter="{{ $image->social_links['twitter'] }}"
                                                                            data-reddit="{{ $image->social_links['reddit'] }}">
                                                                            <i class="fa-solid fa-link"></i>
                                                                        </button>
                                                                        <button class="btn edit "
                                                                            {{ $imageSharedAssetsExist ? 'disabled' : '' }}
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#asset-edit-modal"
                                                                            data-task-id="{{ $image->task->id }}"
                                                                            data-image-id="{{ $image->id }}"
                                                                            data-name="{{ $image->task->name ?? 'No Name' }}"
                                                                            data-campaign= "{{ $image->task->campaign->name }}"
                                                                            data-status="{{ $imageSharedAssetsExist ? 'Shared' : 'Not Shared' }}"
                                                                            data-category="{{ $categoryName }}"
                                                                            data-type="{{ $image->task->asset->type_name }}"
                                                                            data-size="{{ $image->task->size_width }}x{{ $image->task->size_height }} {{ $image->task->size_measurement }}"
                                                                            data-image="{{ Storage::disk('backblaze')->url($image->path) }}"
                                                                            data-partners="{{ json_encode($image->task->campaign->partner) }}"
                                                                            data-link="www.website.com/link-to-asset"
                                                                            data-notes="Additional public facing notes go here">
                                                                            <i class="bx bx-edit"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="acc-grid-view">
                                                <div class="acc-grid list-item-con">
                                                    <div class="row">
                                                        @foreach ($assetsCollection as $image)
                                                            <div class="col-lg-6 col-xl-4">
                                                                <div class="acc-grid-item">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <p class="profile-label">Campaign:</p>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <p class="profile-data">
                                                                                {{ $image->task->campaign->name }}</p>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="list-acc-title">
                                                                                <p>{{ $image->task->name }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="list-item-img">
                                                                                <img src="{{ Storage::disk('backblaze')->url($image->path) }}"
                                                                                    alt="automated-prompt-generation">
                                                                                @if ($imageSharedAssetsExist)
                                                                                    <div class="crew-mark tick">
                                                                                        <svg width="14" height="12"
                                                                                            viewBox="0 0 14 12"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M1.75259 5.82709C1.38891 5.37366 0.757146 5.32772 0.34151 5.72446C-0.0741262 6.12121 -0.116244 6.8104 0.247437 7.26382L3.74743 11.6275C4.13583 12.1117 4.82204 12.1259 5.22702 11.6581L13.727 1.83995C14.1062 1.40194 14.0881 0.711492 13.6866 0.297807C13.2851 -0.115879 12.6522 -0.0961519 12.273 0.341868L4.52826 9.28766L1.75259 5.82709Z"
                                                                                                fill="black"></path>
                                                                                        </svg>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="crew-mark minus">
                                                                                        <i
                                                                                            class="fa-solid fa-exclamation"></i>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <p class="profile-label">Type:</p>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <p class="profile-data">
                                                                                        {{ $image->task->asset->type_name }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            {{-- <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <p class="profile-label">Size:</p>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <p class="profile-data">320kb</p>
                                                                            </div>
                                                                        </div> --}}
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <p class="profile-label">Dimension:</p>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <p class="profile-data">
                                                                                        {{ $image->task->size_width }}
                                                                                        * {{ $image->task->size_height }}
                                                                                        px</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="action-btn-icons">
                                                                                <button
                                                                                    {{ $imageSharedAssetsExist ? '' : 'disabled' }}
                                                                                    class="btn search"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#asset-view-modal"
                                                                                    data-task-id="{{ $image->task->id }}"
                                                                                    data-image-id="{{ $image->id }}"
                                                                                    data-name="{{ $image->task->name ?? 'No Name' }}"
                                                                                    data-campaign= "{{ $image->task->campaign->name }}"
                                                                                    data-status="{{ $imageSharedAssetsExist ? 'Shared' : 'Not Shared' }}"
                                                                                    data-category="{{ $categoryName }}"
                                                                                    data-type="{{ $image->task->asset->type_name }}"
                                                                                    data-size="{{ $image->task->size_width }}x{{ $image->task->size_height }} {{ $image->task->size_measurement }}"
                                                                                    data-start-date="{{ $sharedAsset ? \Carbon\Carbon::parse($sharedAsset->start_date)->format('Y-m-d') : '' }}"
                                                                                    data-end-date="{{ $sharedAsset && $sharedAsset->end_date ? \Carbon\Carbon::parse($sharedAsset->end_date)->format('Y-m-d') : '' }}"
                                                                                    data-image="{{ Storage::disk('backblaze')->url($image->path) }}"
                                                                                    data-partners="{{ json_encode($image->task->campaign->partner) }}"
                                                                                    data-shared-assets="{{ json_encode($image->sharedAssets->pluck('partner_id')->toArray()) }}"
                                                                                    data-image-shared-assets="{{ $imageSharedAssetsExist }}"
                                                                                    data-link="www.website.com/link-to-asset"
                                                                                    data-notes={{ $image->additional_notes }}><i
                                                                                        class="bx bx-search-alt-2"></i></button>
                                                                                <button class="btn new-link"
                                                                                    onclick="openlinkmodel(this)"
                                                                                    data-url="{{ $image->post_url }}"
                                                                                    data-linkedin="{{ $image->social_links['linkedin'] }}"
                                                                                    data-facebook="{{ $image->social_links['facebook'] }}"
                                                                                    data-twitter="{{ $image->social_links['twitter'] }}"
                                                                                    data-reddit="{{ $image->social_links['reddit'] }}">
                                                                                    <i class="fa-solid fa-link"></i>
                                                                                </button>
                                                                                <button class="btn edit "
                                                                                    {{ $imageSharedAssetsExist ? 'disabled' : '' }}
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#asset-edit-modal"
                                                                                    data-task-id="{{ $image->task->id }}"
                                                                                    data-image-id="{{ $image->id }}"
                                                                                    data-name="{{ $image->task->name ?? 'No Name' }}"
                                                                                    data-campaign= "{{ $image->task->campaign->name }}"
                                                                                    data-status="{{ $imageSharedAssetsExist ? 'Shared' : 'Not Shared' }}"
                                                                                    data-category="{{ $categoryName }}"
                                                                                    data-type="{{ $image->task->asset->type_name }}"
                                                                                    data-size="{{ $image->task->size_width }}x{{ $image->task->size_height }} {{ $image->task->size_measurement }}"
                                                                                    data-image="{{ Storage::disk('backblaze')->url($image->path) }}"
                                                                                    data-partners="{{ json_encode($image->task->campaign->partner) }}"
                                                                                    data-link="www.website.com/link-to-asset"
                                                                                    data-notes="Additional public facing notes go here">
                                                                                    <i class="bx bx-edit"></i></button>
                                                                            </div>
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
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Link Asset Modal -->
    <div class="modal fade linkAsset-modal" id="linkAssetModal" tabindex="-1" aria-labelledby="linkAssetModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="linkAssetModalLabel">Link Asset</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row m-0">
                        <div class="col-md-12 mb-4">
                            <h4 class="bold-labels">Public Access</h4>
                            <div class="web-link-col">
                                <div class="row m-0 align-items-center">
                                    <div class="col-9">
                                        <div class="check-list">
                                            <span>Web Link: <span id="assetLink" style="color:#EB8205"></span></span>
                                        </div>
                                    </div>
                                    <div class="col-3 text-end">
                                        <button class=" btn copy-web-link p-0"
                                            onclick="copyToClipboard(document.getElementById('assetLink').textContent)">
                                            <svg width="39" height="39" viewBox="0 0 39 39" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M31.6247 8.62496V23.9583C31.6247 28.1925 28.1922 31.625 23.958 31.625H8.62467C4.39049 31.625 0.958008 28.1925 0.958008 23.9583V8.62496C0.958008 4.39077 4.39049 0.95829 8.62467 0.95829H23.958C28.1922 0.95829 31.6247 4.39077 31.6247 8.62496ZM3.83301 8.62496V23.9583C3.83301 25.2291 4.33784 26.4479 5.23645 27.3465C6.13507 28.2451 7.35385 28.75 8.62467 28.75H23.958C26.6044 28.75 28.7497 26.6047 28.7497 23.9583V8.62496C28.7497 5.97859 26.6044 3.83329 23.958 3.83329H8.62467C5.97831 3.83329 3.83301 5.97859 3.83301 8.62496Z"
                                                    fill="#EB8205" />
                                                <path
                                                    d="M37.3747 12.9375C36.5851 12.9478 35.9475 13.5854 35.9372 14.375V29.7083C35.9267 33.1442 33.1439 35.9269 29.708 35.9375H14.3747C13.5808 35.9375 12.9372 36.5811 12.9372 37.375C12.9372 38.1689 13.5808 38.8125 14.3747 38.8125H29.708C34.7361 38.8125 38.8122 34.7364 38.8122 29.7083V14.375C38.8019 13.5854 38.1643 12.9478 37.3747 12.9375Z"
                                                    fill="#EB8205" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10 col-xl-6 mb-4">
                            <h4 class="bold-labels">Social Media Access</h4>
                            <p>Click on the icons below and follow the instructions to post your banner to your social media
                                account.</p>
                            <div class="social-links">
                                <ul>
                                    <li><a href="#" id="linkmodel-linkedinShare" target="_blank"
                                            aria-label="Share on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></li>
                                    <li><a href="#" id="linkmodel-facebookShare" target="_blank"
                                            aria-label="Share on Facebook"><i class="fa-brands fa-facebook"></i></a></li>
                                    <li><a href="#" id="linkmodel-twitterShare" target="_blank"
                                            aria-label="Share on Twitter"><i class="fa-brands fa-x-twitter"></i></a></li>
                                    <li><a href="#" id="linkmodel-redditShare" target="_blank"
                                            aria-label="Share on Reddit"><i class="fa-brands fa-reddit-alien"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Asset View Modal-->
    <div class="modal fade campaign-modal" id="asset-view-modal" tabindex="-1" aria-labelledby="asset-view-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">ASSET: <span id="asset-name"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body task-modal-details">
                    <div class="edit-modal-content list-item-con">
                        <div class="list-item-img">
                            <img id="asset-image" src="" alt="Asset Image">
                        </div>
                        <div class="list-item-head">
                            <div class="list-acc-title">
                                <p id="modal-task-name"></p>
                            </div>
                            <div class="list-acc-status">
                                <p id="modal-approval-date"></p>
                                <p>|</p>
                                <p id="modal-status">Status: Shared</p>
                                <div class="crew-mark tick">
                                    <svg width="14" height="12" viewBox="0 0 14 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.75259 5.82709C1.38891 5.37366 0.757146 5.32772 0.34151 5.72446C-0.0741262 6.12121 -0.116244 6.8104 0.247437 7.26382L3.74743 11.6275C4.13583 12.1117 4.82204 12.1259 5.22702 11.6581L13.727 1.83995C14.1062 1.40194 14.0881 0.711492 13.6866 0.297807C13.2851 -0.115879 12.6522 -0.0961519 12.273 0.341868L4.52826 9.28766L1.75259 5.82709Z"
                                            fill="black"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <p class="profile-label">Campaign:</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-data" id="modal-campaign-name"></p>
                                    </div>
                                    <div class="col-12">
                                        <p class="profile-label">Share with Partners</p>
                                    </div>
                                    <div class="col-12">
                                        <div class="row partner-scroll" id="partner-list">
                                            <!-- Dynamic partner list will be populated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="profile-label">Start Date:</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <span id="start-date"></span>
                                        {{-- <input type="date" name="start_date" class="date"> --}}
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-label">End Date:</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <span id="end-date"></span>
                                        {{-- <input type="date"  name="end_date" class="date"> --}}
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-label">Category:</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-data" id="modal-category"></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-label">Type:</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-data" id="modal-type"></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-label">Dimension:</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-data" id="modal-dimension"></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-label">Size:</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-data" id="modal-size">320kb</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-12">
                                    <p class="profile-data"><span>Direct Link:</span> <a href="#"
                                            id="modal-link"></a> <i class="fa-solid fa-link"></i></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="profile-data">Send To:</p>
                                </div>
                                <div class="col-sm-6">
                                    <div class="social-links">
                                        <ul>
                                            <li><a href="#" id="linkedinShare" target="_blank"
                                                    aria-label="Share on LinkedIn"><i
                                                        class="fa-brands fa-linkedin"></i></a></li>
                                            <li><a href="#" id="facebookShare" target="_blank"
                                                    aria-label="Share on Facebook"><i
                                                        class="fa-brands fa-facebook"></i></a></li>
                                            <li><a href="#" id="twitterShare" target="_blank"
                                                    aria-label="Share on Twitter"><i
                                                        class="fa-brands fa-x-twitter"></i></a></li>
                                            <li><a href="#" id="redditShare" target="_blank"
                                                    aria-label="Share on Reddit"><i
                                                        class="fa-brands fa-reddit-alien"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <p class="profile-label">Additional Notes:</p>
                                </div>
                                <div class="col-12 filled">
                                    <textarea class="form-control edit-textarea" id="notes" placeholder="Leave a comment here">Additional public facing notes go here</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap mt-3">
                        <div class="sic-btn">
                            <button class="btn link-asset" id="cancel" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade campaign-modal" id="asset-edit-modal" tabindex="-1" aria-labelledby="asset-edit-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit-exampleModalLabel">ASSET: <span id="edit-asset-name"></span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('shared-assets.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="asset_id" name="asset_id">
                    <input type="hidden" id="task_id" name="task_id">


                    <div class="modal-body task-modal-details">
                        <div class="edit-modal-content list-item-con">
                            <div class="list-item-img">
                                <img id="edit-asset-image" src="" alt="Asset Image">
                            </div>
                            <div class="list-item-head">
                                <div class="list-acc-title">
                                    <p id="edit-modal-task-name"></p>
                                </div>
                                <div class="list-acc-status">
                                    <p id="edit-modal-approval-date"></p>
                                    <p>|</p>
                                    <p id="edit-modal-status">Status: Not Shared</p>
                                    <div class="crew-mark minus">
                                        <i class="fa-solid fa-exclamation"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row align-items-center">
                                        <div class="col-sm-6">
                                            <p class="profile-label">Campaign:</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-data" id="edit-modal-campaign-name"></p>
                                        </div>
                                        <div class="col-12">
                                            <p class="profile-label">Share with Partners</p>
                                        </div>
                                        <div class="col-12">
                                            <div class="row partner-scroll" id="edit-partner-list">
                                                <!-- Dynamic partner list will be populated here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="profile-label">Start Date:</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="date" id="edit-start-date" name="edit-start-date"
                                                class="date">

                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-label">End Date:</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="date" id="edit-end-date" name="edit-end-date"
                                                class="date">
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-label">Category:</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-data" id="edit-modal-category"></p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-label">Type:</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-data" id="edit-modal-type"></p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-label">Dimension:</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-data" id="edit-modal-dimension"></p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-label">Size:</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="profile-data" id="edit-modal-size">320kb</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="profile-data"> <span>Direct Link:</span> (link appears once asset has
                                            been
                                            shared) <i class="fa-solid fa-link"></i></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="profile-data">Send To:</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="social-links">
                                            <ul>
                                                <li><a href="#" id="edit-linkedinShare" target="_blank"
                                                        aria-label="Share on LinkedIn"><i
                                                            class="fa-brands fa-linkedin"></i></a></li>
                                                <li><a href="#" id="edit-facebookShare" target="_blank"
                                                        aria-label="Share on Facebook"><i
                                                            class="fa-brands fa-facebook"></i></a></li>
                                                <li><a href="#" id="edit-twitterShare" target="_blank"
                                                        aria-label="Share on Twitter"><i
                                                            class="fa-brands fa-x-twitter"></i></a></li>
                                                <li><a href="#" id="edit-redditShare" target="_blank"
                                                        aria-label="Share on Reddit"><i
                                                            class="fa-brands fa-reddit-alien"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="profile-label">Additional Notes:</p>
                                    </div>
                                    <div class="col-12 ">
                                        <textarea class="form-control edit-textarea" placeholder="Leave a comment here" id="floatingTextarea"
                                            name="additionalnotes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap mt-3">
                            <div class="sic-btn">
                                <button class="btn download" id="save" type="submit">
                                    Update
                                </button>
                            </div>
                            <div class="sic-btn">
                                <button class="btn link-asset" id="cancel" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>





    <!--modal ends  -->
@endsection

@section('script')
    <script>
        const viewModal = document.getElementById('asset-view-modal');
        const editModal = document.getElementById('asset-edit-modal');

        editModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Button that triggered the modal
            const assetName = button.getAttribute('data-name');
            const taskId = button.getAttribute('data-task-id');
            const imageId = button.getAttribute('data-image-id');
            const taskName = button.getAttribute('data-task-name');
            const category = button.getAttribute('data-category');
            const type = button.getAttribute('data-type');
            const size = button.getAttribute('data-size');
            const campaignName = button.getAttribute('data-campaign');
            const image = button.getAttribute('data-image');
            const partners = JSON.parse(button.getAttribute('data-partners'));

            const partnerListContainer = document.getElementById('edit-partner-list');
            partnerListContainer.innerHTML = '';

            document.getElementById('asset_id').value = imageId;
            document.getElementById('task_id').value = taskId;
            document.getElementById('edit-modal-task-name').textContent = assetName;
            document.getElementById('edit-modal-campaign-name').textContent = campaignName;
            document.getElementById('edit-modal-category').textContent = category;
            document.getElementById('edit-modal-type').textContent = type;
            document.getElementById('edit-modal-dimension').textContent = size;
            document.getElementById('edit-asset-image').src = image;

            partners.forEach((partner) => {
                const div = document.createElement('div');
                div.classList.add('col-sm-6', 'd-flex', 'align-items-center', 'gap-2');
                div.innerHTML = `
            <input class="form-check-input mt-0" type="checkbox" name="partners[]"
                value="${partner.partner.id}">
            <p class="profile-data">${partner.partner.name}</p>
            `;

                partnerListContainer.appendChild(div);
            });
        });

        viewModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Button that triggered the modal
            const assetName = button.getAttribute('data-name');
            const taskId = button.getAttribute('data-task-id');
            const imageId = button.getAttribute('data-image-id');
            const taskName = button.getAttribute('data-task-name');
            const category = button.getAttribute('data-category');
            const type = button.getAttribute('data-type');
            const size = button.getAttribute('data-size');
            const campaignName = button.getAttribute('data-campaign');
            const startDate = button.getAttribute('data-start-date');
            const endDate = button.getAttribute('data-end-date');
            const image = button.getAttribute('data-image');
            const link = button.getAttribute('data-link');
            const partners = JSON.parse(button.getAttribute('data-partners'));
            const sharedAssets = JSON.parse(button.getAttribute('data-shared-assets'));
            const imageSharedAssetsExist = button.getAttribute('data-image-shared-assets') === '1';

            const socialLinks = button.getAttribute('data-social-links'); // Expecting a comma-separated list
            const partnerListContainer = document.getElementById('partner-list');
            partnerListContainer.innerHTML = '';

            // Populate modal fields
            // document.getElementById('asset-name').textContent = assetName;
            document.getElementById('modal-task-name').textContent = taskName;
            document.getElementById('modal-campaign-name').textContent = campaignName;
            document.getElementById('modal-category').textContent = category;
            document.getElementById('modal-type').textContent = type;
            document.getElementById('modal-dimension').textContent = size;
            document.getElementById('start-date').textContent = startDate;
            document.getElementById('end-date').textContent = endDate;
            document.getElementById('asset-image').src = image;
            document.getElementById('modal-link').href = link;


            $.ajax({
                url: '/posts/create',
                type: 'POST',
                data: {
                    image: imageId,
                    task_id: taskId,
                    post_type: 'task'
                },
                success: function(response) {
                    console.log(response);
                    // Assuming the response contains post details and share URLs
                    const {
                        postUrl,
                        encodedDescription,
                        socialLinks
                    } = response;

                    // Update modal content with social links
                    $('#modal-link').attr('href', postUrl);
                    $('#modal-link').text(postUrl);
                    $('#linkedinShare').attr('href', socialLinks.linkedin);
                    $('#facebookShare').attr('href', socialLinks.facebook);
                    $('#twitterShare').attr('href', socialLinks.twitter);
                    $('#redditShare').attr('href', socialLinks.reddit);

                },
                error: function(xhr) {
                    $('body').removeClass('loading');
                    console.error(xhr.responseText);
                    alert('An error occurred. Please try again.');
                }
            });

            partners.forEach((partner) => {
                const div = document.createElement('div');
                div.classList.add('col-sm-6', 'd-flex', 'align-items-center', 'gap-2');

                const isChecked = sharedAssets.includes(partner.partner.id);
                const isDisabled = imageSharedAssetsExist ? 'disabled' : '';

                div.innerHTML = `
                <input class="form-check-input mt-0" type="checkbox" name="partners[]"
                    value="${partner.partner.id}"
                    ${isChecked ? 'checked' : ''}
                    ${isDisabled}>
                <p class="profile-data">${partner.partner.name}</p>
            `;

                partnerListContainer.appendChild(div);
            });
            // Additional Notes
            document.getElementById('notes').value = button.getAttribute('data-notes');
        });

        function openlinkmodel(button) {
            let postUrl = button.getAttribute('data-url');
            let linkedin = button.getAttribute('data-linkedin');
            let facebook = button.getAttribute('data-facebook');
            let twitter = button.getAttribute('data-twitter');
            let reddit = button.getAttribute('data-reddit');

            // Update the modal content
            $('#assetLink').text(postUrl);
            $('#linkmodel-linkedinShare').attr('href', linkedin);
            $('#linkmodel-facebookShare').attr('href', facebook);
            $('#linkmodel-twitterShare').attr('href', twitter);
            $('#linkmodel-redditShare').attr('href', reddit);

            // Show the modal
            $('#linkAssetModal').modal('show');
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#tasksTable').DataTable({
                responsive: true,
                pageLength: 10,
            });

            $(".layout-btn").click(function() {
                $(".layout-btn").removeClass("active");
                $(this).addClass("active");
                const targetTable = $(".common-table table");
                targetTable.toggleClass("grid-view", $(this).hasClass("grid"));
                targetTable.toggleClass("list-view", $(this).hasClass("list"));
            });

            $('.download-btn').click(function() {
                const imageUrl = $(this).data('url');
                const link = $('<a>').attr('href', imageUrl).attr('download', '').appendTo('body');
                link[0].click();
                link.remove();
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function createPost(imageId, taskId) {
            $('body').addClass('loading'); // Show loader
            $.ajax({
                url: '/posts/create',
                type: 'POST',
                data: {
                    image: imageId,
                    task_id: taskId,
                    post_type: 'task'
                },
                success: function(response) {
                    $('body').removeClass('loading');
                    // Assuming the response contains post details and share URLs
                    const {
                        postUrl,
                        encodedDescription,
                        socialLinks
                    } = response;

                    // Update modal content with social links
                    $('#assetLink').text(postUrl);
                    $('#linkedinShare').attr('href', socialLinks.linkedin);
                    $('#facebookShare').attr('href', socialLinks.facebook);
                    $('#twitterShare').attr('href', socialLinks.twitter);
                    $('#redditShare').attr('href', socialLinks.reddit);

                    $('#linkAssetModal').modal('show');
                },
                error: function(xhr) {
                    $('body').removeClass('loading');
                    console.error(xhr.responseText);
                    alert('An error occurred. Please try again.');
                }
            });
        }

        function copyToClipboard(publicUrl) {
            // Create a temporary input element
            const tempInput = document.createElement('input');
            tempInput.value = publicUrl;

            // Append the input to the modal instead of the body
            const modal = document.querySelector('.modal.show'); // Select the currently visible modal
            if (modal) {
                modal.appendChild(tempInput);
            } else {
                document.body.appendChild(tempInput); // Fallback to body if no modal is visible
            }

            // Select and copy the text
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');

            // Remove the temporary input element
            tempInput.remove();

            // Show a confirmation message
            alert('Public URL copied to clipboard!');
        }
    </script>
@endsection
