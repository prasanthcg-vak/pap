@extends('layouts.login')
@section('content')
    <!-- ========== Start view-post ========== -->
    <section class="view-post">
        <div class="container">
            <div class="view-post-wrapper">
                <!-- Navbar -->
                <nav class="navbar">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="/home">
                            <img src="{{asset('/assets/images/NewCMLogo2024.svg')}}" alt="logo">
                        </a>
                        <div class="login-btn">
                            <a href="/login" type="button" class="btn">Login</a>
                        </div>
                    </div>
                </nav>
                <!-- Heading -->
                {{-- <h1>Lorem ipsum dolor, sit amet consectetur</h1> --}}
                <!-- View-post-contents -->
                <div class="col-lg-12 View-post-contents">
                    <div class="view-post-image">
                        <img src="assets/images/How-AI-Generative-Chatbots-Boost-Business-Efficiency-and-Profitability-compress.jpg"
                            alt="post-image">
                    </div>
                    <h4 class="sub-heading mt-3">Lorem ipsum dolor sit amet consectetur</h4>
                    <p class="mt-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Libero adipisci hic
                        consequatur error exercitationem nostrum dicta similique iusto quo, corrupti itaque ab natus et
                        minima vero neque sapiente necessitatibus nulla!</p>
                </div>
            </div>
        </div>
    </section>
    <!-- ========== End view-post ========== -->



    @endsection
