<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- dashboard css -->
        <link href="{{ url('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
        <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

        <link rel="shortcut icon" href="{{url('assets/images/favicon.ico')}}">
        <link rel="stylesheet" href="{{url('assets/vendor/daterangepicker/daterangepicker.css')}}">
        <link rel="stylesheet" href="{{url('assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}">
        <script src="{{url('assets/js/config.js')}}"></script>
        <!-- end dashboard css -->

        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
    <!-- Daterangepicker js -->
        <script src="{{url('assets/js/vendor.min.js')}}"></script>
        <script src="{{url('assets/vendor/daterangepicker/moment.min.js')}}"></script>
        <script src="{{url('assets/vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{url('assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
        <script src="{{url('assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js')}}"></script>
        <script src="{{url('assets/js/app.min.js')}}"></script>
</html>
