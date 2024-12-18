@extends('layouts.app')

@section('content')
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!-- profile-content -->
            <div class="profile-content add-a-partner">
                <div class="profile-header">
                    <h3>Edit Partner</h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <form id="Model-Form" action="{{ route('clientpartner.update', $clientPartner->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="profile-con">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="profile-label">Partner Name:</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="partner_name" id="partner_name"
                                            value="{{ old('partner_name', $clientPartner->name) }}"
                                            placeholder="Partner Name" required>
                                        @error('partner_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="profile-con">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="profile-label">Partner Contact:</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="partner_contact" id="partner_contact"
                                            value="{{ old('partner_contact', $clientPartner->contact) }}"
                                            placeholder="Partner Contact" required>
                                        @error('partner_contact')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="profile-con">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="profile-label">Email:</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="email" name="partner_email" id="partner_email"
                                            value="{{ old('partner_email', $clientPartner->email) }}"
                                            placeholder="Partner Email" required>
                                        @error('partner_email')
                                            <span class="text-danger">{{ $message }}</span>
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
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}"
                                                    {{ $group->id = $group_id ? 'selected' : '' }}>{{ $group->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('partner_email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="profile-con add-partner-status">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="profile-label">Status:</p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="radio" id="active" name="status" value="active"
                                            {{ old('status', $clientPartner->is_active) == 1 ? 'checked' : '' }}>
                                        <label for="active">Active</label><br>
                                        <input type="radio" id="inactive" name="status" value="inactive"
                                            {{ old('status', $clientPartner->is_active) == 0 ? 'checked' : '' }}>
                                        <label for="inactive">Inactive</label><br>
                                    </div>
                                </div>
                            </div>
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
                                                            @if ($clientPartner->profile_picture)
                                                                <img src="{{ asset($clientPartner->profile_picture) }}"
                                                                    alt="Partner Logo" style="width: 100px; height: 100px;">
                                                            @else
                                                                <span><img src="assets/images/Image.png"
                                                                        alt=""></span> <br>
                                                            @endif
                                                            <span><img src="assets/images/fi_upload-cloud.svg"
                                                                    alt=""> Upload Image</span>
                                                        </div>
                                                    </div>
                                                    <input type="file" name="logo" class="drop-zone__input">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($returnUrl == 'myprofile')
                                <input type="hidden" name="returnURL" value="myprofile">
                            @else
                                <input type="hidden" name="returnURL" value="partnerlist">
                                <input type="hidden" name="previousPageGroupId" value="{{$previousPageGroupId}}">
                            @endif
                            <div class="submit-button text-end">
                                <button type="submit" class="btn submit-btn">Update Partner</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- profile-content -->
    </div>
@endsection
