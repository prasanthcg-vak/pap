@extends('layouts.login')

@section('content')
@if (session('status'))
    <div class="alert alert-success text-center">
        {{ session('status') }}
    </div>
@elseif (session('error'))
    <div class="alert alert-danger text-center">
        {{ session('error') }}
    </div>
@endif

<section class="cm-signin-page">
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-8">
                <div class="bg-signin">
                    <div class="cm-logo">
                        <img src="{{ asset('/assets/images/New-CMLogo.svg') }}" alt="logo" class="img-fluid">
                    </div>
                    <div class="cm-signin-form">
                        <div class="user-login-fields">

                <div class="card-body">
                    

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                       
                                 <h2 class="signin-title">Partner Asset Portal</h2>
                                    <p class="sub-text">Reset Password</p>
                                    <p class="error_input" style="color: red; text-align: center;"></p>
                                    <div class="email-field">
                                        <input id="email" type="email" class="@error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email"
                                            autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                        {{-- <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 d-none d-lg-block cm-signin-image">
    <div class="circle-gradient"></div>
    <div class="stock-resources">
        <div class="top-notch-btn">
            <a href="#"> <img src="{{ asset('/assets/images/thumbs-up.svg') }}" alt="thumbs-up"
                    class="img-fluid"> Top
                Notch Stock Resources</a>
        </div>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
    </div>
</div>
</div>
</div>
@endsection
