<!-- resources/views/roles/create.blade.php -->

@extends('layouts.app')

@section('content')

<!-- ========== Start CM-main-content ========== -->
<div class="CM-main-content">
    <div class="container-fluid p-0">
    @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- profile-content -->
        <div class="profile-content add-a-partner">
            <div class="profile-header">
                <h3>ADD New Role</h3>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="profile-con">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="profile-label">Role Name:</p>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="profile-con">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="profile-label">Role Description:</p>
                                </div>
                                <div class="col-sm-8">
                                <textarea name="description" class="form-control" id="description" required>{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="profile-con add-partner-status">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="profile-label">Status:</p>
                                </div>
                                <div class="col-sm-8">
                                    <div>
                                        <input type="radio" id="html" name="is_active" value="HTML">
                                        <label for="html">Active</label><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-button">
                        <button type="button" class="btn submit-btn">Submit</button>
                    </div> -->
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="status" class="form-check-input" id="status" value="1" 
                                {{ old('status', 1) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>

                    <button type="submit" class="btn primary-btn">Create Role</button>
                    <a type="button" class="btn link-asset my-4" href="{{ route('roles.index') }}">
                        <i class="fas fa-ban"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    <!-- profile-content -->
</div>
    <!-- ========== End CM-main-content ========== -->
    <!-- <div class="container">
        <h1>Create Role</h1>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description" id="description" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div> -->
@endsection
