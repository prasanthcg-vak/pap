@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="col-sm-6"> </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{$title}}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <label  class="col-sm-3"for="first_name">Name</label>
                                    <span class="col-sm-9">{{$user->name}}</span>
                                </div>
                                <div class="row">
                                    <label  class="col-sm-3"for="state">Email</label>
                                    <span class="col-sm-9">{{$user->email}}</span>
                                </div>
                                <div class="row">
                                    <label  class="col-sm-3"for="role_id">Role</label>
                                    <span class="col-sm-9">{{$user->role->name}}</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group submit-form-group">
                                            <a href="{{$route}}" class="btn btn-danger"> Back </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>                 
@endsection

@section('script')
    <script src="{{ url('public/admin/js/organization.js') }}"></script>
@endsection