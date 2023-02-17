<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Aarti web page">
    <meta name="author" content="XEAM Ventures Private Limited">
    <meta name="keywords" content="Aarti, Aarti Life science, An aarti group company">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />

    <!-- TITLE -->
    <title>{{ env('APP_NAME') }}</title>

    @include('layouts.header_styles')
    @stack('style-scripts')

    <!--- CustomStyle CSS -->
    <link href="{{asset('assets/css/customStyle.css')}}" rel="stylesheet" />

    @yield('style')

    @yield('php')
</head>

<body class="app sidebar-mini">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!--APP-SIDEBAR-->
            <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
            @include('layouts.sidebar')
            <!--/APP-SIDEBAR-->

            <!-- Mobile Header -->
            <div class="app-header header">
                <div class="container-fluid">
                    <div class="d-flex">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="#"></a><!-- sidebar-toggle-->
                        <a class="header-brand1 d-flex d-md-none" href="{{ route('dashboard') }}">
                            <img src="{{ asset('img/logo.png') }}" class="header-brand-img light-logo1" alt="logo">
                        </a><!-- LOGO -->

                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <div class="dropdown d-lg-none d-md-block d-none">
                                <a href="#" class="nav-link icon" data-bs-toggle="dropdown">
                                    <i class="fe fe-search"></i>
                                </a>
                                <div class="dropdown-menu header-search dropdown-menu-start">
                                    <div class="input-group w-100 p-2">
                                        <input type="text" class="form-control" placeholder="Search....">
                                        <div class="input-group-text btn btn-primary">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- SEARCH -->
                            <button class="navbar-toggler navresponsive-toggler d-md-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon fe fe-more-vertical text-dark"></span>
                            </button>
                            <div class="dropdown d-none d-md-flex">
                                <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                    <span class="dark-layout" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Dark Theme"><i class="fe fe-moon"></i></span>
                                    <span class="light-layout" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Light Theme"><i class="fe fe-sun"></i></span>
                                </a>
                            </div><!-- Theme-Layout -->
                            <div class="dropdown d-none d-md-flex">
                                <a class="nav-link icon full-screen-link nav-link-bg">
                                    <i class="fe fe-minimize fullscreen-button"></i>
                                </a>
                            </div><!-- FULL-SCREEN -->

                            <div class="dropdown d-none d-md-flex profile-1">

                                <a href="#" data-bs-toggle="dropdown" class="nav-link pe-2 leading-none d-flex">
                                    <span>
                                        @if(Auth::user()->employee->avatar == '')
                                            <img src="{{ asset(config('constants.uploadPaths.defaultProfilePic')) }}" alt="profile-user" class="avatar  profile-user brround cover-image">
                                        @else
                                        <img src="{{ asset(config('constants.uploadPaths.profilePic').Auth::user()->employee->avatar) }}" alt="profile-user" class="avatar  profile-user brround cover-image">
                                        @endif
                                    </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading">
                                        <div class="text-center">
                                            <h5 class="text-dark mb-0">{{ Auth::user()->name }}</h5>
{{--                                            <small class="text-muted">Administrator</small>--}}
                                        </div>
                                    </div>
                                    <div class="dropdown-divider m-0"></div>

                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="dropdown-icon fe fe-alert-circle"></i>
                                        <span class="side-menu__label">Sign out</span>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </a>
                                    {{-- <a class="dropdown-item" href="login.html">--}}
                                    {{-- <i class="dropdown-icon fe fe-alert-circle"></i> Sign out--}}
                                    {{-- </a>--}}

                                </div>

                            </div>
                            <!-- SIDE-MENU -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- /Mobile Header -->
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    @include('layouts.footer')
    @stack('js-scripts')

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    @yield('script')
</body>

</html>
