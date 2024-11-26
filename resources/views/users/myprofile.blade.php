@extends('layouts.app')

@section('content')

    <!-- ========== Start CM-main-content ========== -->
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!-- profile-content -->
            <div class="profile-content client-profile">
                <div class="profile-header">
                    <h3>Client Profile</h3>
                    <a href="#" class="Edit-My-Profile-btn" data-bs-toggle="modal" data-bs-target="#Edit-My-Profile">Edit
                        Client Profile</a>
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
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="profile-con">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="profile-label">Name:</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="profile-data">{{ ucwords(Auth::user()->name) }}</p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="profile-con">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="profile-label">Company:</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="profile-data">Partners Company</p>
                                </div>
                            </div>
                        </div> --}}
                        <div class="profile-con">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="profile-label">Email:</p>
                                </div>
                                <div class="col-sm-8">
                                    <a href="#" class="profile-data profile-email">{{ Auth::user()->email }}</a>

                                </div>
                            </div>
                        </div>
                        <div class="profile-con">
                            <div class="row">
                                <div class="col-sm-4">
                                </div>
                                <div class="col-sm-8 change-pwd">
                                    <a href="#" class="myprofile Change-password-CP" data-bs-toggle="modal"
                                        data-bs-target="#CP-client-profile">Change Password</a>
                                </div>
                            </div>
                        </div>
                        <div class="profile-con">
                            <div class="row">

                                {{-- <div class="col-sm-8">
                                <p class="profile-data">{{ Auth::user()->active ? 'Active' : 'Inactive' }}</p>
                            </div> --}}
                                <div class="col-sm-4">
                                    <p class="profile-label">Profile:</p>
                                </div>
                                <div class="col-sm-8">
                                    <div style="width:200px; height:200px;">
                                        @if (Auth::user()->profile_picture)
                                            <img src="{{ asset(Auth::user()->profile_picture) }}" alt="Profile Picture"
                                                class="img-thumbnail mt-2" width="100">
                                        @else
                                            <img src="{{ asset('assets/profile_picture/default.png') }}"
                                                alt="Default Profile Picture" class="img-thumbnail mt-2" width="100">
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-con">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="profile-label">Logo:</p>
                                </div>
                                <div class="col-sm-8">
                                    <div class="profile-data client-logo-image">
                                        @if (Auth::user()->profile_picture)
                                            <img src="{{ asset(Auth::user()->profile_picture) }}" alt="logo">
                                        @else
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->hasRolePermission('clientpartner.index'))
                            <div class="profile-con">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="profile-label">Partners :</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="grp-mem">
                                            @foreach ($clientPartners as $clientPartner)
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <a href="#">{{ $clientPartner->partner->name ?? 'N/A' }}</a>
                                                    <div class="add-member-icon me-2">
                                                    </div>
                                                    <div class="">
                                                        @if (Auth::user()->hasRolePermission('clientpartner.edit'))
                                                            <a href="{{ route('clientpartner.edit', $clientPartner->partner_id) }}"
                                                                class="btn search ">
                                                                <i class="bx bx-edit"></i>
                                                            </a>
                                                        @endif
                                                        @if (Auth::user()->hasRolePermission('clientpartner.edit'))
                                                            <form
                                                                action="{{ route('clientpartner.destroy', $clientPartner->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" onclick="this.closest('form').submit();"
                                                                    class="btn trash">
                                                                    <i class="bx bx-trash"></i>
                                                                </a>
                                                            </form>
                                                        @endif

                                                    </div>
                                                </div>
                                            @endforeach
                                            {{-- <div class="d-flex align-items-center justify-content-between mb-2">
                                            <a href="#">Partner Name 02</a>
                                            <div class="add-member-icon me-2">
                                                <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                            </div>
                                            <div class="edit-member-icon">
                                                <a href="#"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <a href="#">Partner Name 03</a>
                                            <div class="add-member-icon me-2">
                                                <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                            </div>
                                            <div class="edit-member-icon">
                                                <a href="#"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                         --}}
                                        </div>

                                    </div>

                                </div>
                                @if (Auth::user()->hasRolePermission('clientpartner.create'))
                                    <a href="{{ route('clientpartner.create') }}" class="Edit-My-Profile-btn"> <i
                                            class="fa-solid fa-plus"></i> Add Partner</a>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- profile-content -->
        </div>
    </div>
    <!-- ========== End CM-main-content ========== -->

    <!-- Edit My Profile Modal -->
    <div class="modal fade Edit-My-Profile-modal" id="Edit-My-Profile" tabindex="-1" aria-labelledby="ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">EDIT CLIENT PROFILE</h1>
                    <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="Model-Form" action="{{ route('profile.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row m-0">

                            <div class="col-lg-12">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{ old('name', ucwords(Auth::user()->name)) }}">
                            </div>

                            <div class="col-lg-12">
                                <label for="contact">Contact</label>
                                <input type="tel" id="contact" name="contact" class="form-control"
                                    value="{{ old('contact', Auth::user()->contact) }}" pattern="[0-9]{10}"
                                    placeholder="Enter a 10-digit phone number" required>
                                <small class="form-text text-muted">Please enter a valid 10-digit phone number.</small>
                            </div>

                            <div class="col-lg-12">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ old('email', Auth::user()->email) }}">
                            </div>
                            {{-- <div class="col-lg-12">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control"
                                    value="{{ old('username', ucwords(Auth::user()->username)) }}">
                            </div> --}}
                            {{-- <div class="col-lg-12">
                            <div class="status-radio-btn">
                                <label for="status">Status</label>
                                <div>
                                    <input type="radio" id="active" name="status" value="active"
                                           {{ Auth::user()->active ? 'checked' : '' }}>
                                    <label for="active">Active</label>
                                </div>
                            </div>
                        </div> --}}
                            <div class="col-lg-12 mb-3">
                                <label for="profile_picture">Profile Picture</label>
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control">

                                @if (Auth::user()->profile_picture)
                                    <img src="{{ asset(Auth::user()->profile_picture) }}" alt="Profile Picture"
                                        class="img-thumbnail mt-2" width="100">
                                @endif
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="profile_picture">Logo</label>
                                <input type="file" id="logo" name="logo" class="form-control">

                                @if (Auth::user()->logo)
                                    <img src="{{ asset(Auth::user()->logo) }}" alt="Logo" class="img-thumbnail mt-2"
                                        width="100">
                                @endif
                            </div>


                        </div>

                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                            <div class="sic-btn">
                                <button class="btn download" id="save">Save</button>
                            </div>
                            <div class="sic-btn">
                                <a class="btn link-asset" id="cancel" data-bs-dismiss="modal" aria-label="Close">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit My Profile Modal -->

    <!-- Change Password Modal -->
    <div class="modal fade Change-password-MP-modal" id="CP-client-profile" tabindex="-1" aria-labelledby="ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">CHANGE PASSWORD</h1>
                    <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal"
                        aria-label="Close">
                </div>
                <div class="modal-body">
                    <form id="Model-Form" action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row m-0">
                            <div class="col-lg-12">
                                <label for="newpassword">New Password</label>
                                <input type="password" id="newpassword" name="newpassword" class="form-control"
                                    placeholder="New Password" required>
                            </div>
                            <div class="col-lg-12">
                                <label for="newpassword_confirmation">Confirm Password</label>
                                <input type="password" id="newpassword_confirmation" name="newpassword_confirmation"
                                    class="form-control" placeholder="Confirm Password" required>
                            </div>
                        </div>
                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                            <div class="sic-btn">
                                <button class="btn download" id="save">Update</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Change Password Modal -->

@endsection
