@extends('layouts.app')

@section('content')
<div class="CM-main-content">
    <div class="container-fluid p-0">
        <!--card-grid-items -->
        <div class="card-grid-contents">
            <div class="card-grid-items">
                @foreach ($assets as $asset)
                    <div class="card-img_text">
                        <div class="Detail-card-image">
                        @php
                            $thumbnail = match($asset['image_type']) {
                                'image' => $asset['image'],
                                'video' => asset('assets/images/video.png'),
                                default => asset('assets/images/document.png'),
                            };
                        @endphp

                        <img src="{{ $thumbnail }}" class="img-fluid" style="max-height: 200px;" alt="{{ $asset['file_name'] }}">
                            {{-- <img src="{{ $asset['file_name'] }}" alt="{{ $asset['file_name'] }}"> --}}
                        </div>
                        {{-- <div class="crew-mark cross">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.369 0.234884C1.05582 -0.0782947 0.548061 -0.0782947 0.234883 0.234884C-0.0782944 0.548063 -0.0782944 1.05583 0.234883 1.36901L4.86588 6.00002L0.234931 10.631C-0.0782463 10.9442 -0.0782466 11.4519 0.234931 11.7651C0.548109 12.0783 1.05587 12.0783 1.36905 11.7651L6 7.13415L10.631 11.7651C10.9441 12.0783 11.4519 12.0783 11.7651 11.7651C12.0782 11.4519 12.0782 10.9442 11.7651 10.631L7.13412 6.00002L11.7651 1.36901C12.0783 1.05583 12.0783 0.548063 11.7651 0.234884C11.4519 -0.0782947 10.9442 -0.0782947 10.631 0.234884L6 4.8659L1.369 0.234884Z"
                                    fill="black" />
                            </svg>
                        </div> --}}
                        <div class="Detail-card-text">
                            <h3>{{ $asset['file_name'] }}</h3>
                            <div class="detail-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7 7C5.34315 7 4 8.34315 4 10C4 11.6569 5.34315 13 7 13C8.65685 13 10 11.6569 10 10C10 8.34315 8.65685 7 7 7ZM6 10C6 9.44772 6.44772 9 7 9C7.55228 9 8 9.44772 8 10C8 10.5523 7.55228 11 7 11C6.44772 11 6 10.5523 6 10Z"
                                        fill="#535584" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3 3C1.34315 3 0 4.34315 0 6V18C0 19.6569 1.34315 21 3 21H21C22.6569 21 24 19.6569 24 18V6C24 4.34315 22.6569 3 21 3H3ZM21 5H3C2.44772 5 2 5.44772 2 6V18C2 18.5523 2.44772 19 3 19H7.31374L14.1924 12.1214C15.364 10.9498 17.2635 10.9498 18.435 12.1214L22 15.6863V6C22 5.44772 21.5523 5 21 5ZM21 19H10.1422L15.6066 13.5356C15.9971 13.145 16.6303 13.145 17.0208 13.5356L21.907 18.4217C21.7479 18.7633 21.4016 19 21 19Z"
                                        fill="#535584" />
                                </svg>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
