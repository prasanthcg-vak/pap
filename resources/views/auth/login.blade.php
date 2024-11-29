@extends('layouts.login')

@section('content')
    <!-- ========== Start sign-in-page ========== -->
    <section class="cm-signin-page">
        <div class="container-fluid">
            <div class="row">
                <!-- Sign-in Form Section -->
                <div class="col-lg-8">
                    <div class="bg-signin">
                        <div class="cm-logo">
                            <img src="{{ asset('/assets/images/NewCMLogo2024.svg') }}" alt="logo" class="img-fluid">
                            
                        </div>
                        <div class="cm-signin-form">
                            <div class="user-login-fields">
                                <form method="POST" action="{{ route('login') }}" id="data-form" class="login-form">
                                    @csrf
                                    <h2 class="signin-title">Digital Asset Portal</h2>
                                    <p class="sub-text">Login into your account</p>
                                    <p class="error_input text-center text-danger"></p>

                                    <!-- Email Field -->
                                    <div class="email-field mb-3">
                                        <input id="email" type="email" 
                                               class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email') }}" required autocomplete="email" 
                                               placeholder="Email" autofocus>
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
                                               name="password" required autocomplete="current-password" 
                                               placeholder="Password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Remember Me & Recover Password -->
                                    <div class="recover-details d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                                            <label class="form-check-label" for="remember-me">Remember me</label>
                                        </div>
                                        <div>
                                            <a href="{{ route('password.request') }}">Recover Password</a>
                                        </div>
                                    </div>

                                    <!-- Login Button -->
                                    <div class="login-button">
                                        <button type="submit" class="btn btn-primary w-100">
                                            Log In
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right-Side Image Section -->
                <div class="col-lg-4 d-none d-lg-block cm-signin-image">
                    <div class="circle-gradient"></div>
                    <div class="stock-resources">
                        <div class="top-notch-btn">
                            <a href="#">
                                <img src="{{ asset('/assets/images/thumbs-up.svg') }}" alt="thumbs-up" class="img-fluid"> 
                                Top Notch Stock Resources
                            </a>
                        </div>
                        {{-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    {{-- <script>
        $(document).ready(function () {
            // Submit form using AJAX
            $('#data-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(form[0]);
                
                // Clear previous errors
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();
                $('.error_input').text('').hide();

                // Show loading (if you have a loading indicator)
                $('#loading').show();

                // Perform AJAX request
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        window.location.href = response.redirect_url;
                    },
                    error: function (response) {
                        $('#loading').hide();

                        if (response.status === 422) {
                            const errors = response.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                const input = form.find(`[name="${key}"]`);
                                input.addClass('is-invalid');
                                input.after(`<span class="invalid-feedback"><strong>${value[0]}</strong></span>`);
                            });
                        }

                        if (response.status === 401) {
                            const errorMsg = response.responseJSON.msg;
                            $('.error_input').text(errorMsg).show().delay(5000).fadeOut('slow');
                        }
                    }
                });
            });
        });
    </script> --}}
@endsection
