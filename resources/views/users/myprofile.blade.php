@extends('layouts.app')

@section('content')

<div class="profile-content">
    <div class="profile-header">
        <h3>Edit Profile</h3>
        
    </div>
    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Name -->
                <div class="profile-con">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="name" class="profile-label">Name:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="{{ old('name', ucwords(Auth::user()->name)) }}" required>
                        </div>
                    </div>
                </div>
                
                <!-- Group -->
                <div class="profile-con">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="group" class="profile-label">Group:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="group" name="group" class="form-control" 
                                   value="{{ old('group', 'Partners Company') }}" disabled>
                        </div>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="profile-con">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="email" class="profile-label">Email:</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="{{ old('email', Auth::user()->email) }}" required>
                        </div>
                    </div>
                </div>
                
                <!-- Username -->
                <div class="profile-con">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="username" class="profile-label">Username:</label>
                        </div>
                        <div class="col-sm-8 change-pwd">
                            <input type="text" id="username" name="username" class="form-control" 
                                   value="{{ old('username', ucwords(Auth::user()->name)) }}" required>
                            {{-- <a href="{{ route('password.change') }}">Change password</a> --}}
                           
                        </div> 
                    </div>
                </div>
                
                <!-- Status -->
                <div class="profile-con">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="status" class="profile-label">Status:</label>
                        </div>
                        <div class="col-sm-8">
                            <select id="status" name="status" class="form-control">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="profile-con">
                    <div class="row">
                        <div class="col-sm-8 offset-sm-4">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <a class="btn btn-secondary" href="">Change password</a>
                            <a href="#" class="btn btn-warning float-right" data-bs-toggle="modal"
                        data-bs-target="#Edit-group-Profile">Edit Group
                        Profile</a>
                        </div>
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade Edit-group-Profile-modal" id="Edit-group-Profile" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">EDIT GROUP PROFILE</h1>
                    <!-- <p class="status green">Active</p> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row m-0">
                            <div class="col-lg-12">
                                <label for="groupname">Group Name</label>
                                <input type="text">
                            </div>
                            <div class="col-lg-12">
                                <label for="groupcontact">Group Conatct</label>
                                <input type="text">
                            </div>
                            <div class="col-lg-12">
                                <label for="email">Email</label>
                                <input type="text">
                            </div>
                            <div class="col-lg-12">
                                <div class="status-radio-btn">
                                    <label for="status">Status</label>
                                    <div>
                                        <input type="radio" id="html" name="fav_language" value="HTML">
                                        <label for="html">Active</label><br>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-lg-12">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="members">Members</label>
                                    <div class="add-edit-members">
                                        <button class="btn add-partner">Add Partner</button>
                                        <button class="btn edit-partner">Edit Partner</button>
                                    </div>
                                </div>
                                <div class="my-2 members-name">
                                    <ul class="ps-0">
                                        <li>
                                            <input type="text">
                                        </li>
                                        <li>
                                            <input type="text">
                                        </li>
                                        <li>
                                            <input type="text">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for="socialmedia">Link Social Media</label>
                                <div class="social-links my-2">
                                    <ul>
                                        <li>
                                            <a href="">
                                                <i class="fa-brands fa-linkedin"></i>
                                            </a>
                                            <input type="text" placeholder="linkedin">
                                        </li>
                                        <li>
                                            <a href="">
                                                <i class="fa-brands fa-facebook"></i>
                                            </a>
                                            <input type="text" placeholder="facebook">
                                        </li>
                                        <li>
                                            <a href="">
                                                <i class="fa-brands fa-x-twitter"></i>
                                            </a>
                                            <input type="text" placeholder="twitter">
                                        </li>
                                        <li>
                                            <a href="">
                                                <i class="fa-brands fa-reddit-alien"></i>
                                            </a>
                                            <input type="text" placeholder="reddit">
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                            <div class="sic-btn">
                                <button class="btn download" id="save">
                                    save
                                </button>
                            </div>
                            <div class="sic-btn">
                                <button class="btn link-asset" id="cancel" data-bs-dismiss="modal" aria-label="Close">
                                    cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
