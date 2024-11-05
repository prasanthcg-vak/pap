@extends('layouts.app')

@section('content')
    <!-- Table -->
    <div class="campaingn-table pb-3 common-table">

        <!-- campaigns-contents -->
        <div class="col-lg-12 task campaigns-contents">
            <div class="campaigns-title">
                <h3>CAMPAIGNS</h3>
            </div>
            
            <a href="#" class="create-task-btn" data-bs-toggle="modal" data-bs-target="#addEditModal">Create Campaign</a>

        </div>
        <!-- campaigns-contents -->
        <div class="table-wrapper">
            <table id="add-row">
                <thead>
                    <tr>
                        <!-- <th class="folder">
                                <span>
                                    <svg width="22" height="24" viewBox="0 0 22 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11 9.75C16.3848 9.75 20.75 7.73528 20.75 5.25C20.75 2.76472 16.3848 0.75 11 0.75C5.61522 0.75 1.25 2.76472 1.25 5.25C1.25 7.73528 5.61522 9.75 11 9.75Z"
                                            stroke="black" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M1.25 5.25C1.25253 9.76548 4.35614 13.688 8.75 14.729V21C8.75 22.2426 9.75736 23.25 11 23.25C12.2426 23.25 13.25 22.2426 13.25 21V14.729C17.6439 13.688 20.7475 9.76548 20.75 5.25"
                                            stroke="black" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </th> -->
                        <th class="campaingn-title">
                            <span>S.No</span>
                        </th>
                        <th class="campaingn-title1">
                            <span>Name</span>
                        </th>
                        <th>
                            <span>Description</span>
                        </th>
                        <th>
                            <span>Due Date</span>
                        </th>
                        <th class="description">
                            <span>Status</span>
                        </th>
                        <th class="active">
                            <span>Active / InActive</span>
                        </th>
                        <th class="active">
                            <span>Action</span>
                        </th>
                    </tr>
                </thead>
                {{-- <tbody>
                    <tr>
                        <!-- <td class="folder">
                            <span>
                                <svg width="27" height="25" viewBox="0 0 27 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_52_7280)">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M25 8.06678H23.7179V5.58469C23.7179 4.90026 23.1429 4.34365 22.4359 4.34365H14.1026C13.9526 4.34365 13.8077 4.29277 13.6923 4.20031L10.0244 1.24104H2.5641C1.85705 1.24104 1.28205 1.79765 1.28205 2.48209V6.82574H25V8.06678H1.28205H0V2.48209C0 1.11322 1.15 0 2.5641 0H10.2564C10.4064 0 10.5513 0.0508828 10.6667 0.14334L14.3346 3.10261H22.4359C23.85 3.10261 25 4.21582 25 5.58469V8.06678Z"
                                            fill="#5D5FEF" />
                                        <path
                                            d="M25 8.06678H23.7179V5.58469C23.7179 4.90026 23.1429 4.34365 22.4359 4.34365H14.1026C13.9526 4.34365 13.8077 4.29277 13.6923 4.20031L10.0244 1.24104H2.5641C1.85705 1.24104 1.28205 1.79765 1.28205 2.48209V6.82574H25V8.06678H1.28205H0V2.48209C0 1.11322 1.15 0 2.5641 0H10.2564C10.4064 0 10.5513 0.0508828 10.6667 0.14334L14.3346 3.10261H22.4359C23.85 3.10261 25 4.21582 25 5.58469V8.06678Z"
                                            stroke="white" stroke-width="0.2" />
                                        <rect y="6" width="27" height="18" rx="2" fill="#A5A6F6"
                                            stroke="#5D5FEF" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_52_7280">
                                            <rect width="27" height="25" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        </td> -->
                        <td class="campaingn-title">
                            <span>Campaign 5</span>
                        </td>
                        <td>
                            <span>asset</span>
                        </td>
                        <td>
                            <span>Task</span>
                        </td>
                        <td class="description">
                            <span>Short overview goes here Short overview goes here Short overview goes here
                            </span>
                        </td>
                        <td class="active">
                            <div class="action-btn-group">
                                <div class="left-group">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn view-btn" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        view
                                    </button>
                                    <!-- Modal added below -->
                                    <!-- Modal ends -->
                                    <button class="btn btn-default close-btn">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.369 0.234884C1.05582 -0.0782947 0.548061 -0.0782947 0.234883 0.234884C-0.0782944 0.548063 -0.0782944 1.05583 0.234883 1.36901L4.86588 6.00002L0.234931 10.631C-0.0782463 10.9442 -0.0782466 11.4519 0.234931 11.7651C0.548109 12.0783 1.05587 12.0783 1.36905 11.7651L6 7.13415L10.631 11.7651C10.9441 12.0783 11.4519 12.0783 11.7651 11.7651C12.0782 11.4519 12.0782 10.9442 11.7651 10.631L7.13412 6.00002L11.7651 1.36901C12.0783 1.05583 12.0783 0.548063 11.7651 0.234884C11.4519 -0.0782947 10.9442 -0.0782947 10.631 0.234884L6 4.8659L1.369 0.234884Z"
                                                fill="#5D5FEF" />
                                        </svg>
                                    </button>
                                    <button class="btn btn-default expand-btn">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8.10789 2.39197V1.22531H12.7746V5.89197H11.6079V3.21695L8.23726 6.58758L7.4123 5.76262L10.783 2.39197H8.10789Z"
                                                fill="#FF0000" />
                                            <path
                                                d="M2.39176 8.10803H1.2251V12.7747H5.89176V11.608H3.21672L6.58737 8.23738L5.76241 7.41242L2.39176 10.7831V8.10803Z"
                                                fill="#FF0000" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                   
                    
                </tbody> --}}
            </table>
        </div>
    </div>
    <!-- Table -->

    <!-- Modal contents -->

    <!-- Modal table-view-task  -->
    <div class="modal fade campaign-modal" id="" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">CAMPAIGN
                        5 - ASSETS</h1>
                    <p class="status green">Active</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--card-grid-items -->
                    <div class="card-grid-contents">
                        <div class="card-grid-items">
                            <div class="card-img_text">
                                <div class="Detail-card-image">
                                    <img src="assets/images/automated-prompt-generation-with-generative-ai 1.png"
                                        alt="automated-prompt-generation">
                                </div>
                                <div class="crew-mark cross">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.369 0.234884C1.05582 -0.0782947 0.548061 -0.0782947 0.234883 0.234884C-0.0782944 0.548063 -0.0782944 1.05583 0.234883 1.36901L4.86588 6.00002L0.234931 10.631C-0.0782463 10.9442 -0.0782466 11.4519 0.234931 11.7651C0.548109 12.0783 1.05587 12.0783 1.36905 11.7651L6 7.13415L10.631 11.7651C10.9441 12.0783 11.4519 12.0783 11.7651 11.7651C12.0782 11.4519 12.0782 10.9442 11.7651 10.631L7.13412 6.00002L11.7651 1.36901C12.0783 1.05583 12.0783 0.548063 11.7651 0.234884C11.4519 -0.0782947 10.9442 -0.0782947 10.631 0.234884L6 4.8659L1.369 0.234884Z"
                                            fill="black" />
                                    </svg>

                                </div>
                                <div class="Detail-card-text">
                                    <h3>Details of Image Name</h3>
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
                            <div class="card-img_text">
                                <div class="Detail-card-image">
                                    <img src="assets/images/cascade-boat-clean-china-natural-rural 1.png"
                                        alt="cascade-boat-clean-china-natural">
                                </div>
                                <div class="crew-mark tick">
                                    <svg width="14" height="12" viewBox="0 0 14 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.75259 5.82709C1.38891 5.37366 0.757146 5.32772 0.34151 5.72446C-0.0741262 6.12121 -0.116244 6.8104 0.247437 7.26382L3.74743 11.6275C4.13583 12.1117 4.82204 12.1259 5.22702 11.6581L13.727 1.83995C14.1062 1.40194 14.0881 0.711492 13.6866 0.297807C13.2851 -0.115879 12.6522 -0.0961519 12.273 0.341868L4.52826 9.28766L1.75259 5.82709Z"
                                            fill="black" />
                                    </svg>

                                </div>
                                <div class="Detail-card-text">
                                    <h3>Details of Image Name</h3>
                                    <div class="detail-icon">
                                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.1604 11.5356C15.8504 11.5356 14.2109 11.7597 13.6746 11.8385C11.4548 9.56553 10.8232 8.27353 10.6812 7.92906C10.8737 7.44404 11.5435 5.60153 11.6387 3.23514C11.6857 2.05035 11.4304 1.16506 10.8799 0.603853C10.3303 0.0435736 9.66504 0 9.47428 0C8.8054 0 7.68331 0.331651 7.68331 2.55257C7.68331 4.47958 8.5996 6.52434 8.85288 7.05267C7.51843 10.8626 6.08575 13.4707 5.78152 14.007C0.419324 15.9867 0 17.9007 0 18.4432C0 19.418 0.708041 20 1.894 20C4.77542 20 7.40489 15.2566 7.83966 14.4329C9.88615 13.6334 12.6252 13.1382 13.3216 13.02C15.3189 14.8856 17.6288 15.3833 18.588 15.3833C19.3098 15.3833 21 15.3833 21 13.6794C21.0001 12.097 18.9317 11.5356 17.1604 11.5356ZM17.0215 12.6543C18.5779 12.6543 18.9892 13.159 18.9892 13.4258C18.9892 13.5932 18.9244 14.1396 18.0901 14.1396C17.3421 14.1396 16.0505 13.7156 14.7797 12.7958C15.3097 12.7275 16.0938 12.6543 17.0215 12.6543ZM9.39258 1.08429C9.5345 1.08429 9.62792 1.12899 9.70494 1.23374C10.1526 1.84278 9.79165 3.83281 9.35235 5.3901C8.92828 4.0548 8.61003 2.00591 9.0578 1.28509C9.14533 1.1444 9.24538 1.08429 9.39258 1.08429ZM8.63672 13.0041C9.2002 11.8879 9.83176 10.2612 10.1758 9.34122C10.8641 10.471 11.79 11.52 12.3255 12.0887C10.6582 12.4333 9.39672 12.7777 8.63672 13.0041ZM1.1193 18.5921C1.08218 18.5489 1.07669 18.4579 1.10467 18.3487C1.16334 18.1197 1.61165 16.9847 4.85427 15.5625C4.38996 16.2796 3.66411 17.3042 2.86671 18.0697C2.3054 18.5849 1.86833 18.8461 1.56762 18.8461C1.46005 18.8461 1.31183 18.8173 1.1193 18.5921Z"
                                                fill="#535584" />
                                        </svg>

                                    </div>
                                </div>
                            </div>
                            <div class="card-img_text">
                                <div class="Detail-card-image">
                                    <img src="assets/images/digital-art-snow-landscape 1.png" alt="digital-art-snow">
                                </div>
                                <div class="crew-mark minus">
                                    <svg width="15" height="2" viewBox="0 0 15 2" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.0217 0L0.97826 0C0.437982 0 -9.53674e-07 0.447715 -9.53674e-07 1C-9.53674e-07 1.55228 0.437982 2 0.97826 2L14.0217 2C14.562 2 15 1.55228 15 1C15 0.447715 14.562 0 14.0217 0Z"
                                            fill="black" />
                                    </svg>

                                </div>
                                <div class="Detail-card-text">
                                    <h3>Details of Image Name</h3>
                                    <div class="detail-icon">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.3425 1.75H3.6575C2.60402 1.75 1.75 2.60402 1.75 3.6575V17.3425C1.75 18.396 2.60402 19.25 3.6575 19.25H17.3425C18.396 19.25 19.25 18.396 19.25 17.3425V3.6575C19.25 2.60402 18.396 1.75 17.3425 1.75Z"
                                                stroke="#535584" stroke-width="1.33333" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M6.125 1.75V19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M14.875 1.75V19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M1.75 10.5H19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M1.75 6.125H6.125" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M1.75 14.875H6.125" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M14.875 14.875H19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M14.875 6.125H19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                            </div>
                            <div class="card-img_text card-rectangle">
                                <div class="Detail-card-image">
                                    <img src="assets/images/autumn-forest-lake-landscape 1.png" alt="autumn-forest-lake">
                                </div>
                                <div class="crew-mark cross">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.369 0.234884C1.05582 -0.0782947 0.548061 -0.0782947 0.234883 0.234884C-0.0782944 0.548063 -0.0782944 1.05583 0.234883 1.36901L4.86588 6.00002L0.234931 10.631C-0.0782463 10.9442 -0.0782466 11.4519 0.234931 11.7651C0.548109 12.0783 1.05587 12.0783 1.36905 11.7651L6 7.13415L10.631 11.7651C10.9441 12.0783 11.4519 12.0783 11.7651 11.7651C12.0782 11.4519 12.0782 10.9442 11.7651 10.631L7.13412 6.00002L11.7651 1.36901C12.0783 1.05583 12.0783 0.548063 11.7651 0.234884C11.4519 -0.0782947 10.9442 -0.0782947 10.631 0.234884L6 4.8659L1.369 0.234884Z"
                                            fill="black" />
                                    </svg>

                                </div>
                                <div class="Detail-card-text">
                                    <h3>Details of Image Name</h3>
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
                            <div class="card-img_text">
                                <div class="Detail-card-image">
                                    <img src="assets/images/automated-prompt-generation-with-generative-ai 1.png"
                                        alt="automated-prompt-generation">
                                </div>
                                <div class="crew-mark cross">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.369 0.234884C1.05582 -0.0782947 0.548061 -0.0782947 0.234883 0.234884C-0.0782944 0.548063 -0.0782944 1.05583 0.234883 1.36901L4.86588 6.00002L0.234931 10.631C-0.0782463 10.9442 -0.0782466 11.4519 0.234931 11.7651C0.548109 12.0783 1.05587 12.0783 1.36905 11.7651L6 7.13415L10.631 11.7651C10.9441 12.0783 11.4519 12.0783 11.7651 11.7651C12.0782 11.4519 12.0782 10.9442 11.7651 10.631L7.13412 6.00002L11.7651 1.36901C12.0783 1.05583 12.0783 0.548063 11.7651 0.234884C11.4519 -0.0782947 10.9442 -0.0782947 10.631 0.234884L6 4.8659L1.369 0.234884Z"
                                            fill="black" />
                                    </svg>

                                </div>
                                <div class="Detail-card-text">
                                    <h3>Details of Image Name</h3>
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
                            <div class="card-img_text card-rectangle">
                                <div class="Detail-card-image">
                                    <img src="assets/images/autumn-forest-lake-landscape 1.png" alt="autumn-forest-lake">
                                </div>
                                <div class="crew-mark cross">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.369 0.234884C1.05582 -0.0782947 0.548061 -0.0782947 0.234883 0.234884C-0.0782944 0.548063 -0.0782944 1.05583 0.234883 1.36901L4.86588 6.00002L0.234931 10.631C-0.0782463 10.9442 -0.0782466 11.4519 0.234931 11.7651C0.548109 12.0783 1.05587 12.0783 1.36905 11.7651L6 7.13415L10.631 11.7651C10.9441 12.0783 11.4519 12.0783 11.7651 11.7651C12.0782 11.4519 12.0782 10.9442 11.7651 10.631L7.13412 6.00002L11.7651 1.36901C12.0783 1.05583 12.0783 0.548063 11.7651 0.234884C11.4519 -0.0782947 10.9442 -0.0782947 10.631 0.234884L6 4.8659L1.369 0.234884Z"
                                            fill="black" />
                                    </svg>

                                </div>
                                <div class="Detail-card-text">
                                    <h3>Details of Image Name</h3>
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
                            <div class="card-img_text">
                                <div class="Detail-card-image">
                                    <img src="assets/images/cascade-boat-clean-china-natural-rural 1.png"
                                        alt="cascade-boat-clean-china-natural">
                                </div>
                                <div class="crew-mark tick">
                                    <svg width="14" height="12" viewBox="0 0 14 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.75259 5.82709C1.38891 5.37366 0.757146 5.32772 0.34151 5.72446C-0.0741262 6.12121 -0.116244 6.8104 0.247437 7.26382L3.74743 11.6275C4.13583 12.1117 4.82204 12.1259 5.22702 11.6581L13.727 1.83995C14.1062 1.40194 14.0881 0.711492 13.6866 0.297807C13.2851 -0.115879 12.6522 -0.0961519 12.273 0.341868L4.52826 9.28766L1.75259 5.82709Z"
                                            fill="black" />
                                    </svg>

                                </div>
                                <div class="Detail-card-text">
                                    <h3>Details of Image Name</h3>
                                    <div class="detail-icon">
                                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.1604 11.5356C15.8504 11.5356 14.2109 11.7597 13.6746 11.8385C11.4548 9.56553 10.8232 8.27353 10.6812 7.92906C10.8737 7.44404 11.5435 5.60153 11.6387 3.23514C11.6857 2.05035 11.4304 1.16506 10.8799 0.603853C10.3303 0.0435736 9.66504 0 9.47428 0C8.8054 0 7.68331 0.331651 7.68331 2.55257C7.68331 4.47958 8.5996 6.52434 8.85288 7.05267C7.51843 10.8626 6.08575 13.4707 5.78152 14.007C0.419324 15.9867 0 17.9007 0 18.4432C0 19.418 0.708041 20 1.894 20C4.77542 20 7.40489 15.2566 7.83966 14.4329C9.88615 13.6334 12.6252 13.1382 13.3216 13.02C15.3189 14.8856 17.6288 15.3833 18.588 15.3833C19.3098 15.3833 21 15.3833 21 13.6794C21.0001 12.097 18.9317 11.5356 17.1604 11.5356ZM17.0215 12.6543C18.5779 12.6543 18.9892 13.159 18.9892 13.4258C18.9892 13.5932 18.9244 14.1396 18.0901 14.1396C17.3421 14.1396 16.0505 13.7156 14.7797 12.7958C15.3097 12.7275 16.0938 12.6543 17.0215 12.6543ZM9.39258 1.08429C9.5345 1.08429 9.62792 1.12899 9.70494 1.23374C10.1526 1.84278 9.79165 3.83281 9.35235 5.3901C8.92828 4.0548 8.61003 2.00591 9.0578 1.28509C9.14533 1.1444 9.24538 1.08429 9.39258 1.08429ZM8.63672 13.0041C9.2002 11.8879 9.83176 10.2612 10.1758 9.34122C10.8641 10.471 11.79 11.52 12.3255 12.0887C10.6582 12.4333 9.39672 12.7777 8.63672 13.0041ZM1.1193 18.5921C1.08218 18.5489 1.07669 18.4579 1.10467 18.3487C1.16334 18.1197 1.61165 16.9847 4.85427 15.5625C4.38996 16.2796 3.66411 17.3042 2.86671 18.0697C2.3054 18.5849 1.86833 18.8461 1.56762 18.8461C1.46005 18.8461 1.31183 18.8173 1.1193 18.5921Z"
                                                fill="#535584" />
                                        </svg>

                                    </div>
                                </div>
                            </div>
                            <div class="card-img_text">
                                <div class="Detail-card-image">
                                    <img src="assets/images/digital-art-snow-landscape 1.png" alt="digital-art-snow">
                                </div>
                                <div class="crew-mark minus">
                                    <svg width="15" height="2" viewBox="0 0 15 2" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.0217 0L0.97826 0C0.437982 0 -9.53674e-07 0.447715 -9.53674e-07 1C-9.53674e-07 1.55228 0.437982 2 0.97826 2L14.0217 2C14.562 2 15 1.55228 15 1C15 0.447715 14.562 0 14.0217 0Z"
                                            fill="black" />
                                    </svg>

                                </div>
                                <div class="Detail-card-text">
                                    <h3>Details of Image Name</h3>
                                    <div class="detail-icon">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.3425 1.75H3.6575C2.60402 1.75 1.75 2.60402 1.75 3.6575V17.3425C1.75 18.396 2.60402 19.25 3.6575 19.25H17.3425C18.396 19.25 19.25 18.396 19.25 17.3425V3.6575C19.25 2.60402 18.396 1.75 17.3425 1.75Z"
                                                stroke="#535584" stroke-width="1.33333" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M6.125 1.75V19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M14.875 1.75V19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M1.75 10.5H19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M1.75 6.125H6.125" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M1.75 14.875H6.125" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M14.875 14.875H19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M14.875 6.125H19.25" stroke="#535584" stroke-width="1.33333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                            </div>
                            <div class="card-img_text card-rectangle">
                                <div class="Detail-card-image">
                                    <img src="assets/images/autumn-forest-lake-landscape 1.png" alt="autumn-forest-lake">
                                </div>
                                <div class="crew-mark cross">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.369 0.234884C1.05582 -0.0782947 0.548061 -0.0782947 0.234883 0.234884C-0.0782944 0.548063 -0.0782944 1.05583 0.234883 1.36901L4.86588 6.00002L0.234931 10.631C-0.0782463 10.9442 -0.0782466 11.4519 0.234931 11.7651C0.548109 12.0783 1.05587 12.0783 1.36905 11.7651L6 7.13415L10.631 11.7651C10.9441 12.0783 11.4519 12.0783 11.7651 11.7651C12.0782 11.4519 12.0782 10.9442 11.7651 10.631L7.13412 6.00002L11.7651 1.36901C12.0783 1.05583 12.0783 0.548063 11.7651 0.234884C11.4519 -0.0782947 10.9442 -0.0782947 10.631 0.234884L6 4.8659L1.369 0.234884Z"
                                            fill="black" />
                                    </svg>

                                </div>
                                <div class="Detail-card-text">
                                    <h3>Details of Image Name</h3>
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
                        </div>
                    </div>
                    <!--card-grid-items -->
                </div>
            </div>
        </div>
    </div>
    <!--modal table-view-task ends  -->


    <!-- createTask Modal -->
    <div class="modal fade createTask-modal" id="add_edit_Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h1 class="modal-title fs-5" id="exampleModalLabel">Create / Edit Campaign</h1> --}}
                    {{-- <p class="status green">Active</p> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="showresults">
                    
                </div>
                
            </div>
        </div>
    </div>
    <!-- End createTask Modal -->
@endsection
@section('script')
    <script>
        $(function() {
            var table = $('#add-row').DataTable({
                /* "paging": true,
                "lengthChange": true,
                "info": true,
                "autoWidth": false,
                "searching": false,
                "responsive": true, */
                "search": {
                    regex: true
                },
                "destory": true,
                "bDestroy": true,
                "pageLength": 10,
                "ordering": true,
                "processing": true,
                "serverSide": true,
                language: {
                    "search": "",
                    // "lengthMenu": "Show",
                    "sLengthMenu": " _MENU_ ",
                    searchPlaceholder: "Search",
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>', // or '→'
                        previous: '<i class="fas fa-chevron-left"></i>' // or '←' 
                    }
                },
                dom: 'frtlip',
                "searching": true,
                "autoWidth": false,
                "responsive": true,
                "columnDefs": [{
                    "orderable": false,
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                }],
                'select': {
                    'style': 'multi'
                },
                order: [
                    [1, "asc"]
                ],
                ajax: "{{ route('getcampaignslist') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'due_date'
                    },
                    {
                        data: 'status_id'
                    },
                    {
                        data: 'is_active'
                    },
                    {
                        data: 'action'
                    },
                ],
            });


            $(document).on("click", ".add_edit_modal_load", function() {
                $url = $(this).data("url");
                $('#loading').show();
                $.ajax({
                    url: $url,
                    type: "GET",
                    dataType: "html",
                    success: function(data) {
                        $('#loading').hide();
                        $('#showresults').html(data);
                        $('#add_edit_Modal').modal('show');
                    },
                    error: function(xhr, status) {
                        alert("Sorry, there was a problem!");
                    },
                    complete: function(xhr, status) {
                        //$('#showresults').slideDown('slow')
                    }
                });
                
            });

        });
    </script>
    <script>
         $(document).on("submit", "form#data-form", function(e) {
        // $('form#data-form').on('submit', function(e) {
            e.preventDefault();
            var _this = $(this);
            let data = new FormData(_this[0]);
            $('#loading').show();
            if (_this.find('input[name=_method]').val()) {
                var _method = _this.find('input[name=_method]').val();
            } else {
                var _method = 'POST';
            }
            if (_method === "DELETE") {
                var status = confirm("Are you sure you want to delete ?");
                if (status == false) {
                    $('#loading').hide();
                    return false;
                }
            }
            _this.find('span.help-block').remove();
            _this.find('div.form-group').removeClass('has-error');
            // $('#loading').hide();
            $.ajax({
                url: _this.attr('action'),
                type: 'post',
                data,
                contentType: 'multipart/form-data',
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#loading').hide();
                    if (_method === "DELETE") {
                        //   window.location.href = response.redirect_url;
                    } else {
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(response) {
                    $('#loading').hide();
                    var response = $.parseJSON(response.responseText);
                    $.each(response.errors, function(key, value) {
                        if (key == 'permission_check') {
                            _this.find('div.permission_check').after(
                                '<span class="help-block"> ' + value + ' </span>');
                        }
                        if (key == 'user_role_id') {
                            _this.find('div.user_role_id').after('<span class="help-block"> ' +
                                value + ' </span>');
                        }
                        _this.find('input[name=' + key + '], select[name=' + key +
                            '], textarea[name=' + key + ']').parent().addClass('has-error');
                        _this.find('input[name=' + key + '], select[name=' + key +
                            '], textarea[name=' + key + ']').after(
                            '<span class="help-block"> ' + value + ' </span>');
                    });
                }
            })

        });
    </script>
@endsection
