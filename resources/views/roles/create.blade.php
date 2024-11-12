@extends('layouts.app')

@section('content')

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
                    <button type="submit" class="btn primary-btn">Create Role</button>
                    <a type="button" class="btn link-asset my-4" href="{{ route('roles.index') }}">
                        <i class="fas fa-ban"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
