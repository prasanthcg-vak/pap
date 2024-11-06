@extends('layouts.app')

@section('content')

<!-- ========== Start CM-main-content ========== -->
<div class="CM-main-content">
    <div class="container-fluid p-0">
        <!-- profile-content -->
        <div class="profile-content">
            <div class="profile-header">
                <h3>My Profile</h3>
                <a href="#" class="Edit-My-Profile-btn" data-bs-toggle="modal"
                   data-bs-target="#Edit-My-Profile">Edit My Profile</a>
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
                    <div class="profile-con">
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="profile-label">Group:</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="profile-data">Partners Company</p>
                            </div>
                        </div>
                    </div>
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
                                <p class="profile-label">Username:</p>
                            </div>
                            <div class="col-sm-8 change-pwd">
                                <p class="profile-data">{{ ucwords(Auth::user()->username) }}</p>
                                <a href="#" class="myprofile Change-password-CP" data-bs-toggle="modal"
                                   data-bs-target="#CP-client-profile">Change Password</a>
                            </div>
                        </div>
                    </div>
                    <div class="profile-con">
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="profile-label">Status:</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="profile-data">{{ Auth::user()->active ? 'Active' : 'Inactive' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- profile-content -->
    </div>
</div>
<!-- ========== End CM-main-content ========== -->

<!-- Edit My Profile Modal -->
<div class="modal fade Edit-My-Profile-modal" id="Edit-My-Profile" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">EDIT MY PROFILE</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row m-0">
                        <div class="col-lg-12">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   value="{{ old('name', ucwords(Auth::user()->name)) }}">
                        </div>
                        <div class="col-lg-12">
                            <label for="group">Group</label>
                            <input type="text" id="group" name="group" class="form-control" disabled
                                   value="{{ old('group', 'Partners Company') }}">
                        </div>
                        <div class="col-lg-12">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                   value="{{ old('email', Auth::user()->email) }}">
                        </div>
                        <div class="col-lg-12">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-control"
                                   value="{{ old('username', ucwords(Auth::user()->username)) }}">
                        </div>
                        <div class="col-lg-12">
                            <div class="status-radio-btn">
                                <label for="status">Status</label>
                                <div>
                                    <input type="radio" id="active" name="status" value="active"
                                           {{ Auth::user()->active ? 'checked' : '' }}>
                                    <label for="active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                        <div class="sic-btn">
                            <button class="btn download" id="save">Save</button>
                        </div>
                        <div class="sic-btn">
                            <button class="btn link-asset" id="cancel" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit My Profile Modal -->

<!-- Change Password Modal -->
<div class="modal fade Change-password-MP-modal" id="CP-client-profile" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">CHANGE PASSWORD</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row m-0">
                        <div class="col-lg-12">
                            <label for="newpassword">New Password</label>
                            <input type="password" id="newpassword" name="newpassword" class="form-control"
                                   placeholder="New Password">
                        </div>
                        <div class="col-lg-12">
                            <label for="confirmpassword">Confirm Password</label>
                            <input type="password" id="confirmpassword" name="confirmpassword" class="form-control"
                                   placeholder="Confirm Password">
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
