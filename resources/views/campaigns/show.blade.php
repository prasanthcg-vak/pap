@extends('layouts.app')

@section('content')

<style>
.img-container .image-wrapper {
    height: 150px; /* Set a fixed height for consistent display */
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
                        <div class="title_status">
                            <h3>{{$campaign->name}}</h3>
                            <p class="status {{ $campaign->is_active ? 'green' : 'red' }}">
                                {{ $campaign->is_active ? 'Active' : 'Inactive' }}
                            </p>
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
                                                $thumbnail = match($img['image_type']) {
                                                    'image' => $img['url'],
                                                    'video' => asset('assets/images/video.png'),
                                                    default => asset('assets/images/document.png'),
                                                };
                                            @endphp
                                            <div class="item py-3">
                                            <a href="{{ route('campaigns.assetsview', ['id' => $img['image_id']]) }}" >
                                                    <div class="card-img_text">
                                                        <div class="Detail-card-image">
                                                            <img src="{{ $thumbnail }}"
                                                                alt="{{ $img['name'] }}" class="w-100">
                                                        </div>
                                                        {{-- <div class="crew-mark cross">
                                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M1.369 0.234884C1.05582 -0.0782947 0.548061 -0.0782947 0.234883 0.234884C-0.0782944 0.548063 -0.0782944 1.05583 0.234883 1.36901L4.86588 6.00002L0.234931 10.631C-0.0782463 10.9442 -0.0782466 11.4519 0.234931 11.7651C0.548109 12.0783 1.05587 12.0783 1.36905 11.7651L6 7.13415L10.631 11.7651C10.9441 12.0783 11.4519 12.0783 11.7651 11.7651C12.0782 11.4519 12.0782 10.9442 11.7651 10.631L7.13412 6.00002L11.7651 1.36901C12.0783 1.05583 12.0783 0.548063 11.7651 0.234884C11.4519 -0.0782947 10.9442 -0.0782947 10.631 0.234884L6 4.8659L1.369 0.234884Z"
                                                                    fill="black" />
                                                            </svg>
                                                        </div> --}}
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
                        <h3>{{$campaign->name}} - TASKS</h3>
                    </div>
                    <div class="campaingn-table common-table">
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
                                            <span>description</span>
                                        </th>
                                        <th class="">
                                            <span>active</span>
                                        </th>
                                        {{-- <th class="">
                                            <span>action</span>
                                        </th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $task)
                                    <tr>
                                        <td class="campaingn-title">
                                            <span>{{$task->name}}</span>
                                        </td>
                                        <td>
                                            <span>{{$campaign->name}}</span>
                                        </td>
                                        <td>
                                            <span>{{$task->date_required}}</span>
                                        </td>
                                        <td class="description">
                                            <span>{!! $task->description !!}
                                            </span>
                                        </td>
                                        <td class="">
                                            <span>{{ optional($task->status)->name ?? 'No Status' }}</span>
                                        </td>
                                        {{-- <td class=""> --}}
                                            {{-- <span><div class="action-btn-icons "> --}}
                                                {{-- <button class="btn search"><i class='bx bx-search-alt-2'></i></button> --}}
                                                {{-- <a href="{{ route('tasks.show', $task->id) }}" class="btn search"><i --}}
                                                        {{-- class="fa fa-eye" title="show"></i></a> --}}

                                                {{-- <button class="btn edit"><i class='bx bx-edit'></i></button> --}}
                                            {{-- </div></span> --}}
                                        {{-- </td> --}}
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


@endsection