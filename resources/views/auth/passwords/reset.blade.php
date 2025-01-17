@extends('layouts.login')

@section('content')
<section class="cm-signin-page">
    <div class="container-fluid">
        <div class="row">
            <!-- Reset Password Form Section -->
            <div class="col-lg-8">
                <div class="bg-signin">
                    <div class="cm-logo">
                        <img src="{{ asset('/assets/images/NewCMLogo2024.svg') }}" alt="logo" class="img-fluid">
                    </div>
                    <div class="cm-signin-form">
                        <div class="user-login-fields">
                            <form method="POST" action="{{ route('password.update') }}" id="data-form" class="login-form">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <h2 class="signin-title">Reset Password</h2>
                                <p class="sub-text">Enter your new password</p>
                                <p class="error_input text-center text-danger"></p>

                                <!-- Email Field -->
                                <div class="email-field mb-3">
                                    <input id="email" type="email" 
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ $email ?? old('email') }}" 
                                           required autocomplete="email" placeholder="Email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div class="password-field mb-3">
                                    <input id="password" type="password" 
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password" required autocomplete="new-password" 
                                           placeholder="New Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Confirm Password Field -->
                                <div class="password-field mb-3">
                                    <input id="password-confirm" type="password" 
                                           class="form-control"
                                           name="password_confirmation" required 
                                           autocomplete="new-password" placeholder="Confirm Password">
                                </div>

                                <!-- Reset Password Button -->
                                <div class="login-button">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right-Side Image Section -->
            <div class="col-lg-4 d-none d-lg-block cm-signin-image">
                {{-- Additional content for right-side image (if required) --}}
            </div>
        </div>
    </div>
</section>

@endsection
