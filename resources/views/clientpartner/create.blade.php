    <!-- resources/views/clientpartner/create.blade.php -->
    @extends('layouts.app')

    @section('content')
        <div class="CM-main-content">
            <div class="container-fluid p-0">
                <!-- profile-content -->
                <div class="profile-content add-a-partner">
                    <div class="profile-header">
                        <h3>ADD A PARTNER</h3>
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
                    <form id="Model-Form" action="{{ route('clientpartner.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Partner Name -->
                                <div class="profile-con">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="profile-label">Partner Name:</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="partner_name" id="partner_name"
                                                placeholder="Partner Name" required value="{{ old('partner_name') }}">
                                            @error('partner_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Partner Contact -->
                                <div class="profile-con">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="profile-label">Partner Contact:</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="partner_contact" id="partner_contact"
                                                placeholder="Partner Contact" required value="{{ old('partner_contact') }}">
                                            @error('partner_contact')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="profile-con">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="profile-label">Email:</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="email" name="partner_email" id="partner_email"
                                                placeholder="Partner Email" required value="{{ old('partner_email') }}">
                                            @error('partner_email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-con">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="profile-label">Client Group:</p>
                                        </div>
                                        <div class="col-sm-8">

                                            <select name="group" class="form-select  common-select" id="group_id">
                                                <option value="">Select Group</option>
                                                @foreach ($groups as $group)
                                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('group')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Status -->
                                <div class="profile-con add-partner-status">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="profile-label">Status:</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <div>
                                                <input type="radio" id="active" name="status" value="active"
                                                    {{ old('status') == 'active' ? 'checked' : '' }}>
                                                <label for="active">Active</label><br>
                                                <input type="radio" id="inactive" name="status" value="inactive"
                                                    {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                                <label for="inactive">Inactive</label><br>
                                            </div>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Partner Logo -->
                                <div class="profile-con">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="profile-label">Partner Logo:</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="additional-img">
                                                <div class="upload--col">
                                                    <div class="drop-zone">
                                                        <div class="drop-zone__prompt">
                                                            <div class="drop-zone_color-txt">
                                                                <span><img src="{{ asset('assets/images/Image.png') }}"
                                                                        alt=""></span> <br>
                                                                <span><img
                                                                        src="{{ asset('assets/images/fi_upload-cloud.svg') }}"
                                                                        alt=""> Upload Image</span>
                                                            </div>
                                                        </div>
                                                        <input type="file" name="logo" class="drop-zone__input">
                                                    </div>
                                                </div>


                                            </div>
                                            @error('logo')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="submit-button text-end">
                                                <button type="submit" class="btn submit-btn">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- profile-content -->
            </div>
        </div>


    @endsection
