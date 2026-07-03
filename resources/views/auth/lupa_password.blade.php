<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-wide customizer-hide" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ __('messages.forgot_password_title') }} - {{ __('messages.landing_title') }}</title>

    <meta name="description" content="{{ __('messages.forgot_password_description') }}" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Forgot Password -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-heading fw-bold">{{ __('messages.landing_title') }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <div class="text-end mb-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class='bx bx-globe'></i> {{ app()->getLocale() == 'en' ? __('messages.indonesian') : __('messages.english') }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(app()->getLocale() == 'en')
                                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'id') }}">🌐 Bahasa Indonesia</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🌐 English</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <h4 class="mb-1">{{ __('messages.forgot_password_title') }}</h4>
                        <p class="mb-6">{{ __('messages.forgot_password_description') }}</p>

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-6" action="{{ route('password.reset.send') }}"
                            method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">{{ __('messages.email') }}</label>
                                @error('email')
                                    <div class="alert alert-danger py-2 mb-2" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="{{ __('messages.enter_your_email') }}" autofocus required />
                            </div>
                            <button class="btn btn-primary d-grid w-100" type="submit">{{ __('messages.send_reset_link') }}</button>
                        </form>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl icon-base"></i> {{ __('messages.back_to_login') }}
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
