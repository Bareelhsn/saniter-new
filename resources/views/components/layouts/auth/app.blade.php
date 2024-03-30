<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        {{-- Variable dari atribut x-app-layout component view --}}
        <title>{{ $title }} - Saniter</title>

        {{-- Asset Css --}}
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/logo-qinar.ico') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
        <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('assets/js/config.js') }}"></script>

        {{-- Custom style (jika ada) --}}
        {{ $style ?? null }}
    </head>

    <body>
        <div class="container-xxl">

            {{-- Isi di dalam tag x-app-layout component --}}
            {{ $slot }}

        </div>

        {{-- Asset Javascript --}}
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <script async defer src="https://buttons.github.io/buttons.js"></script>

        {{-- Sweet Alert --}}
        @include('sweetalert::alert')

        <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>

        {{-- Custom script (jika ada) --}}
        {{ $script ?? null }}
    </body>
</html>
