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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}" />

    <!-- TITLE -->
    <title>Login</title>

    <!-- BOOTSTRAP CSS -->
    <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/dark-style.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/skin-modes.css')}}" rel="stylesheet" />

    <!-- SINGLE-PAGE CSS -->
    <link href="{{asset('assets/plugins/single-page/css/main.css')}}" rel="stylesheet" type="text/css">

    <!--- FONT-ICONS CSS -->
    <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{asset('assets/colors/color1.css')}}" />

    <!--- CustomStyle CSS -->
    <link href="{{asset('assets/css/customStyle.css')}}" rel="stylesheet" />

    <style>
        #left_section {
            flex-basis: 60%;
            background: url('../../../assets/images/background/login_bg_1.jpg') left top no-repeat;
            position: relative;
        }

        #left_section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            background-color: rgba(3, 105, 57, 0.9);
            width: 100%;
            height: 100%;
        }

        #right_section {
            flex-basis: 40%;
        }

        @media screen and (max-width: 768px) {
            #right_section {
                flex-basis: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- GLOABAL LOADER -->
    <!-- <div id="global-loader" style="display: none;">
        <img src="{{ asset('img/loader.svg') }}" class="loader-img" alt="Loader">
    </div> -->
    <!-- /GLOABAL LOADER -->

    <div class="w-100 h-100">
        <div class="d-flex align-items-center h-100">
            <div id="left_section" class="align-self-stretch d-md-block d-none">
                <div class="h-100 overflow-hidden">
                    <!-- <img src="{{ asset(('assets/images/background/login_bg.jpg')) }}" class="m-0" alt="Logo"> -->
                </div>
            </div>
            <div id="right_section">
                <div class="container-login100">
                    <div class="wrap-login100 p-0">
                        <div class="card-header justify-content-center">
                            <div class="d-inline-block">
                                <img src="{{ asset(config('constants.static.logo')) }}" class="header-brand-img m-0" alt="Logo">
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @elseif(session()->has('error_attempt'))
                            <div class="alert alert-info alert-dismissible">
                                {{ session()->get('error_attempt') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                                <form method="POST" action="{{ route('login') }}">
                                    {{ csrf_field() }}
                                <span class="login100-form-title"> Email </span>
                                <div class="wrap-input100 validate-input" data-bs-validate="Valid Employee Code is required">
                                    <input class="input100" type="text" name="employee_code" placeholder="Employee Code">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100"> <i class="zmdi zmdi-email" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="wrap-input100 validate-input" data-bs-validate="Password is required">
                                    <input class="input100" type="password" name="password" id="password" placeholder="Password"> <span class="focus-input100"></span> <span class="symbol-input100"> <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="text-end pt-1">
                                    <p class="mb-0"><a href="{{ url('forgot-password') }}" class="text-primary ms-1">Forgot Password?</a></p>
                                </div>
                                <div class="container-login100-form-btn">
                                    <button type="submit" class="login100-form-btn btn-orange">
                                        Login
                                    </button>
                                </div>
                                {{-- <div class="text-center pt-3">
                                <p class="text-dark mb-0">Not a member?<a href="register.html"
                                        class="text-primary ms-1">Create an Account</a></p>
                            </div> --}}
                            </form>
                        </div>
                    </div>
                </div> <!-- CONTAINER CLOSED -->
            </div>
        </div>
    </div>

    <!-- JQUERY JS -->
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{asset('assets/plugins/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

    <!-- CUSTOM JS-->
    <script src="{{asset('assets/js/custom.js')}}"></script>
</body>

</html>
