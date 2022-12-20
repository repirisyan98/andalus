@php
    $themeClass = '';
    if (!empty($_COOKIE['theme'])) {
        if ($_COOKIE['theme'] == 'dark-theme') {
            $themeClass = 'dark-theme';
        } else {
            $themeClass = 'light-theme';
        }
    }
@endphp
<!doctype html>
<html lang="en" class="{{ $themeClass }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/ico" />
    <!--plugins-->
    @yield('style')
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" /> --}}
    <title>@yield('title')</title>
    @livewireStyles
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--start header -->
        @include('layouts.header')
        <!--end header -->
        <!--navigation-->
        @include('layouts.nav')
        <!--end navigation-->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                @yield('wrapper')
                <!--end row-->
            </div>
        </div>

        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2022. Cluster Andalus</p>
        </footer>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <!--app JS-->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @livewireScripts
    @yield('script')
    @if (!empty($_COOKIE['theme']))
        @if ($_COOKIE['theme'] == 'dark-theme')
            <link id="alert-dark" rel="stylesheet" href="{{ asset('assets/css/sweetalert_alert_dark.css') }}"
                rel="stylesheet">
        @endif
    @endif
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <x-livewire-alert::scripts />
</body>

</html>
