<div class="topbar">
    @php
        // dd(Auth::user()->client->logo);
    @endphp
    <nav class="navbar">
        <div class="container-fluid">
            <div class="logo_title">

                <a class="navbar-brand" href="/home">

                    @if (Auth::user()->roles->first()->role_level > 3)
                        <img src="{{ Auth::user()->client && Auth::user()->client->logo
                            ? asset(Auth::user()->client->logo)
                            : asset('/assets/images/NewCMLogo2024.svg') }}"
                            alt="logo" class="img-fluid">
                    @else
                        <img src="{{ asset('/assets/images/NewCMLogo2024.svg') }}" alt="logo" href="#"
                            class="img-fluid">
                    @endif

                </a>
                <span>Digital Asset Portal</span>
            </div>

            <div class="profile-image">
                <div class="profile-name" style="display:inline-grid;">
                    <span style="font-size:15px; font-weight:700;">Welcome {{ Auth::user()->name }}</span>
                    <span class="role"
                        style="font-size:12px;">{{ Auth::user()->roles->first()->name ?? 'No Role Assigned' }}</span>
                </div>
                <div class="dropdown">

                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <img src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('assets/images/Image.png') }}"
                            alt="profile-image" class="img-fluid">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                        <li><a class="dropdown-item" href="{{ url('myprofile') }}">My Profile</a></li>
                        {{-- <li><a class="dropdown-item" href="#">Group Profile</a></li> --}}
                        <li><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- bottom-navigation -->
    <div class="bottom-navigation">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                                href="{{ route('home') }}">
                                Home
                            </a>
                        </li>
                        @if (Auth::user()->hasRolePermission('clients.index') || Auth::user()->hasRolePermission('client-groups.index'))
                            <li class="nav-item dropdown  ">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('clients.index') ? 'active' : '' }} {{ request()->routeIs('client-groups.index') ? 'active' : '' }}"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Client Management
                                    <i class="fas fa-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    @if (Auth::user()->hasRolePermission('clients.index'))
                                        <li>
                                            <a class="dropdown-item  {{ request()->routeIs('clients.index') ? 'active' : '' }}"
                                                href="{{ route('clients.index') }}">
                                                Clients
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->hasRolePermission('client-groups.index'))
                                        <li>
                                            <a class="dropdown-item  {{ request()->routeIs('client-groups.index') ? 'active' : '' }}"
                                                href="{{ route('client-groups.index') }}">
                                                Client Groups
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        @if (Auth::user()->hasRolePermission('users.index'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}">
                                    User Management
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->hasRolePermission('roles.index'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}"
                                    href="{{ route('roles.index') }}">
                                    Role Management
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->hasRolePermission('asset-types.index') || Auth::user()->hasRolePermission('categories.index'))
                            <li class="nav-item dropdown  ">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('asset-types.index') ? 'active' : '' }} {{ request()->routeIs('categories.index') ? 'active' : '' }}"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Task Management
                                    <i class="fas fa-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    @if (Auth::user()->hasRolePermission('asset-types.index'))
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('asset-types.index') ? 'active' : '' }}"
                                                href="{{ route('asset-types.index') }}">
                                                Assets Type
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->hasRolePermission('categories.index'))
                                        <li>
                                            <a class="dropdown-item  {{ request()->routeIs('categories.index') ? 'active' : '' }}"
                                                href="{{ route('categories.index') }}">
                                                Categories
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        {{-- @if (Auth::user()->hasRolePermission('impersonateLogs'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('impersonateLogs') ? 'active' : '' }}"
                                    href="{{ route('impersonateLogs') }}">
                                    Impersonate Logs
                                </a>
                            </li>
                        @endif --}}

                    </ul>
                    <div class="float-left">
                        @if (session('impersonator_id'))
                            <a href="{{ route('stop-impersonation') }}" class="btn btn-warning">
                                Return to Admin Account
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!--fixed menu-->
    <div class="fixed-box-area">
        <div class="container-fluid p-0">
            <!-- top-card-items -->
            <div class="top-card-items">
                @if (Auth::user()->hasRolePermission('campaigns.index'))
                    <a href="{{ route('campaigns.index') }}"
                        class="card-item purple  {{ request()->routeIs('campaigns.index') ? 'active' : '' }}">
                        <div class="circle_text">
                            <div class="circle-icon purple">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_177_180)">
                                        <path
                                            d="M10 9.99968C9.20435 9.99968 8.44129 10.3158 7.87868 10.8784C7.31607 11.441 7 12.204 7 12.9997C7 13.7953 7.31607 14.5584 7.87868 15.121C8.44129 15.6836 9.20435 15.9997 10 15.9997H14C14.7956 15.9997 15.5587 15.6836 16.1213 15.121C16.6839 14.5584 17 13.7953 17 12.9997C17 12.204 16.6839 11.441 16.1213 10.8784C15.5587 10.3158 14.7956 9.99968 14 9.99968H10ZM15 12.9997C15 13.2649 14.8946 13.5193 14.7071 13.7068C14.5196 13.8943 14.2652 13.9997 14 13.9997H10C9.73478 13.9997 9.48043 13.8943 9.29289 13.7068C9.10536 13.5193 9 13.2649 9 12.9997C9 12.7345 9.10536 12.4801 9.29289 12.2926C9.48043 12.105 9.73478 11.9997 10 11.9997H14C14.2652 11.9997 14.5196 12.105 14.7071 12.2926C14.8946 12.4801 15 12.7345 15 12.9997ZM17 18.9997C17 19.2649 16.8946 19.5193 16.7071 19.7068C16.5196 19.8943 16.2652 19.9997 16 19.9997H8C7.73478 19.9997 7.48043 19.8943 7.29289 19.7068C7.10536 19.5193 7 19.2649 7 18.9997C7 18.7345 7.10536 18.4801 7.29289 18.2926C7.48043 18.105 7.73478 17.9997 8 17.9997H16C16.2652 17.9997 16.5196 18.105 16.7071 18.2926C16.8946 18.4801 17 18.7345 17 18.9997ZM19.536 3.12068L17.878 1.46468C17.4149 0.998931 16.864 0.629642 16.2572 0.378178C15.6504 0.126713 14.9998 -0.00193367 14.343 -0.000320712H8C6.67441 0.00126715 5.40356 0.528561 4.46622 1.4659C3.52888 2.40324 3.00159 3.67408 3 4.99968V18.9997C3.00159 20.3253 3.52888 21.5961 4.46622 22.5335C5.40356 23.4708 6.67441 23.9981 8 23.9997H16C17.3256 23.9981 18.5964 23.4708 19.5338 22.5335C20.4711 21.5961 20.9984 20.3253 21 18.9997V6.65668C21.0019 5.99977 20.8735 5.349 20.6222 4.74205C20.3709 4.1351 20.0017 3.58401 19.536 3.12068ZM18.122 4.53468C18.2627 4.67711 18.3893 4.83284 18.5 4.99968H16V2.49968C16.1671 2.60921 16.3226 2.73553 16.464 2.87668L18.122 4.53468ZM19 18.9997C19 19.7953 18.6839 20.5584 18.1213 21.121C17.5587 21.6836 16.7956 21.9997 16 21.9997H8C7.20435 21.9997 6.44129 21.6836 5.87868 21.121C5.31607 20.5584 5 19.7953 5 18.9997V4.99968C5 4.20403 5.31607 3.44097 5.87868 2.87836C6.44129 2.31575 7.20435 1.99968 8 1.99968H14V4.99968C14 5.53011 14.2107 6.03882 14.5858 6.41389C14.9609 6.78897 15.4696 6.99968 16 6.99968H19V18.9997Z"
                                            fill="white" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_177_180">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="card-text">
                                <p>CAMPAIGNS</p>
                            </div>
                        </div>
                        <div class="values purple">
                            <span>{{ campaigns_count() }}</span>
                        </div>
                    </a>
                @endif
                @if (Auth::user()->hasRolePermission('tasks.index'))
                    <a href="{{ route('tasks.index') }}"
                        class="card-item green  {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                        <div class="circle_text ">
                            <div class="circle-icon green">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_177_313)">
                                        <path
                                            d="M22.4851 10.9753L12.0001 17.2673L1.51512 10.9753C1.2877 10.8389 1.01538 10.7983 0.758084 10.8627C0.500784 10.927 0.279576 11.0909 0.143122 11.3183C0.00666773 11.5457 -0.0338539 11.818 0.0304711 12.0753C0.0947961 12.3326 0.258699 12.5539 0.486122 12.6903L11.4861 19.2903C11.6417 19.3837 11.8197 19.4331 12.0011 19.4331C12.1826 19.4331 12.3606 19.3837 12.5161 19.2903L23.5161 12.6903C23.7435 12.5539 23.9075 12.3326 23.9718 12.0753C24.0361 11.818 23.9956 11.5457 23.8591 11.3183C23.7227 11.0909 23.5015 10.927 23.2442 10.8627C22.9869 10.7983 22.7145 10.8389 22.4871 10.9753H22.4851Z"
                                            fill="white" />
                                        <path
                                            d="M22.4852 15.5428L12.0002 21.8339L1.51524 15.5428C1.40263 15.4753 1.27782 15.4306 1.14793 15.4112C1.01803 15.3919 0.885605 15.3983 0.758203 15.4302C0.630801 15.462 0.51092 15.5187 0.405405 15.5969C0.299889 15.675 0.210806 15.7732 0.143241 15.8858C0.0756756 15.9985 0.030952 16.1233 0.0116234 16.2532C-0.00770524 16.3831 -0.0012603 16.5155 0.0305902 16.6429C0.0949152 16.9002 0.258818 17.1214 0.486241 17.2578L11.4862 23.8579C11.6418 23.9513 11.8198 24.0007 12.0012 24.0007C12.1827 24.0007 12.3607 23.9513 12.5162 23.8579L23.5162 17.2578C23.7437 17.1214 23.9076 16.9002 23.9719 16.6429C24.0362 16.3856 23.9957 16.1133 23.8592 15.8858C23.7228 15.6584 23.5016 15.4945 23.2443 15.4302C22.987 15.3659 22.7147 15.4064 22.4872 15.5428H22.4852Z"
                                            fill="white" />
                                        <path
                                            d="M11.9999 14.7734C11.4605 14.7731 10.9313 14.6262 10.4689 14.3484L0.484928 8.35742C0.337075 8.26852 0.214739 8.14288 0.129809 7.99271C0.0448784 7.84254 0.000244141 7.67295 0.000244141 7.50042C0.000244141 7.3279 0.0448784 7.15831 0.129809 7.00814C0.214739 6.85797 0.337075 6.73233 0.484928 6.64342L10.4689 0.652422C10.9313 0.374651 11.4605 0.227905 11.9999 0.227905C12.5393 0.227905 13.0686 0.374651 13.5309 0.652422L23.5149 6.64342C23.6628 6.73233 23.7851 6.85797 23.87 7.00814C23.955 7.15831 23.9996 7.3279 23.9996 7.50042C23.9996 7.67295 23.955 7.84254 23.87 7.99271C23.7851 8.14288 23.6628 8.26852 23.5149 8.35742L13.5309 14.3484C13.0685 14.6262 12.5393 14.7731 11.9999 14.7734ZM2.94393 7.50042L11.4999 12.6334C11.651 12.7238 11.8238 12.7716 11.9999 12.7716C12.176 12.7716 12.3488 12.7238 12.4999 12.6334L21.0559 7.50042L12.4999 2.36742C12.3488 2.27703 12.176 2.22929 11.9999 2.22929C11.8238 2.22929 11.651 2.27703 11.4999 2.36742L2.94393 7.50042Z"
                                            fill="white" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_177_313">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>

                            </div>
                            <div class="card-text">
                                <p>TASKS</p>
                            </div>
                        </div>
                        <div class="values green">
                            <span>{{ task_count() }}</span>
                        </div>
                    </a>
                @endif
                @if (Auth::user()->hasRolePermission('library.index'))
                    <a href="{{ route('library.index') }}"
                        class="card-item orange {{ request()->routeIs('library.index') ? 'active' : '' }}">
                        <div class="circle_text ">
                            <div class="circle-icon orange">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_177_260)">
                                        <path
                                            d="M3.99993 6.00012C3.60568 6.00115 3.21514 5.924 2.8509 5.77312C2.48667 5.62224 2.15597 5.40063 1.87793 5.12112L0.333929 3.74712C0.135812 3.57048 0.0159778 3.32238 0.000787327 3.05739C-0.0144031 2.7924 0.076295 2.53223 0.252929 2.33412C0.429563 2.136 0.677665 2.01616 0.942654 2.00097C1.20764 1.98578 1.46781 2.07648 1.66593 2.25312L3.25093 3.66712C3.34167 3.76901 3.45229 3.85126 3.57601 3.90881C3.69972 3.96635 3.8339 3.99797 3.97029 4.00171C4.10668 4.00546 4.24239 3.98126 4.36907 3.93059C4.49576 3.87993 4.61074 3.80388 4.70693 3.70712L8.31093 0.276116C8.50472 0.103586 8.75799 0.0131348 9.01723 0.0238668C9.27648 0.0345988 9.5214 0.145674 9.70027 0.333631C9.87914 0.521588 9.97795 0.77171 9.97584 1.03117C9.97372 1.29062 9.87084 1.5391 9.68893 1.72412L6.09993 5.13812C5.8237 5.41273 5.49605 5.63021 5.13572 5.77812C4.77539 5.92603 4.38944 6.00147 3.99993 6.00012ZM23.9999 4.00012C23.9999 3.7349 23.8946 3.48055 23.707 3.29301C23.5195 3.10547 23.2651 3.00012 22.9999 3.00012H12.9999C12.7347 3.00012 12.4804 3.10547 12.2928 3.29301C12.1053 3.48055 11.9999 3.7349 11.9999 4.00012C11.9999 4.26533 12.1053 4.51969 12.2928 4.70722C12.4804 4.89476 12.7347 5.00012 12.9999 5.00012H22.9999C23.2651 5.00012 23.5195 4.89476 23.707 4.70722C23.8946 4.51969 23.9999 4.26533 23.9999 4.00012ZM6.09993 13.1381L9.68893 9.72412C9.7891 9.63494 9.87031 9.52652 9.92772 9.40531C9.98514 9.2841 10.0176 9.15259 10.0231 9.01858C10.0287 8.88458 10.0072 8.75083 9.96002 8.62529C9.91282 8.49976 9.84085 8.385 9.74839 8.28784C9.65593 8.19069 9.54487 8.11312 9.42183 8.05977C9.29878 8.00641 9.16626 7.97835 9.03214 7.97725C8.89803 7.97616 8.76507 8.00206 8.64117 8.0534C8.51727 8.10474 8.40496 8.18048 8.31093 8.27612L4.71093 11.7071C4.52056 11.8891 4.26732 11.9907 4.00393 11.9907C3.74054 11.9907 3.4873 11.8891 3.29693 11.7071L1.70693 10.1221C1.51833 9.93996 1.26572 9.83916 1.00353 9.84144C0.741331 9.84372 0.490519 9.94889 0.305111 10.1343C0.119703 10.3197 0.0145336 10.5705 0.0122552 10.8327C0.00997675 11.0949 0.110771 11.3475 0.292929 11.5361L1.87793 13.1211C2.4376 13.6809 3.19585 13.9968 3.98743 14C4.77901 14.0032 5.53977 13.6934 6.10393 13.1381H6.09993ZM23.9999 12.0001C23.9999 11.7349 23.8946 11.4805 23.707 11.293C23.5195 11.1055 23.2651 11.0001 22.9999 11.0001H12.9999C12.7347 11.0001 12.4804 11.1055 12.2928 11.293C12.1053 11.4805 11.9999 11.7349 11.9999 12.0001C11.9999 12.2653 12.1053 12.5197 12.2928 12.7072C12.4804 12.8948 12.7347 13.0001 12.9999 13.0001H22.9999C23.2651 13.0001 23.5195 12.8948 23.707 12.7072C23.8946 12.5197 23.9999 12.2653 23.9999 12.0001ZM6.09993 21.1381L9.68493 17.7241C9.7851 17.6349 9.86631 17.5265 9.92372 17.4053C9.98114 17.2841 10.0136 17.1526 10.0191 17.0186C10.0247 16.8846 10.0032 16.7508 9.95601 16.6253C9.90882 16.4998 9.83685 16.385 9.74439 16.2878C9.65193 16.1907 9.54087 16.1131 9.41783 16.0598C9.29478 16.0064 9.16226 15.9783 9.02814 15.9773C8.89403 15.9762 8.76107 16.0021 8.63717 16.0534C8.51327 16.1047 8.40096 16.1805 8.30693 16.2761L4.70693 19.7071C4.61074 19.8039 4.49576 19.8799 4.36907 19.9306C4.24239 19.9813 4.10668 20.0055 3.97029 20.0017C3.8339 19.998 3.69972 19.9664 3.57601 19.9088C3.45229 19.8513 3.34167 19.769 3.25093 19.6671L1.66593 18.2531C1.46781 18.0765 1.20764 17.9858 0.942654 18.001C0.677665 18.0162 0.429563 18.136 0.252929 18.3341C0.076295 18.5322 -0.0144031 18.7924 0.000787327 19.0574C0.0159778 19.3224 0.135812 19.5705 0.333929 19.7471L1.87793 21.1211C2.4376 21.6809 3.19585 21.9968 3.98743 22C4.77901 22.0032 5.53977 21.6934 6.10393 21.1381H6.09993ZM23.9999 20.0001C23.9999 19.7349 23.8946 19.4805 23.707 19.293C23.5195 19.1055 23.2651 19.0001 22.9999 19.0001H12.9999C12.7347 19.0001 12.4804 19.1055 12.2928 19.293C12.1053 19.4805 11.9999 19.7349 11.9999 20.0001C11.9999 20.2653 12.1053 20.5197 12.2928 20.7072C12.4804 20.8948 12.7347 21.0001 12.9999 21.0001H22.9999C23.2651 21.0001 23.5195 20.8948 23.707 20.7072C23.8946 20.5197 23.9999 20.2653 23.9999 20.0001Z"
                                            fill="white" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_177_260">
                                            <rect width="24" height="24" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>

                            </div>
                            <div class="card-text">
                                <p>LIBRARY</p>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
            <!-- top-card-items  -->
        </div>
    </div>
    <!--fixed menu-->
</div>
