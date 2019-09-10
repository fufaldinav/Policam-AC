<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Template Basic Images Start -->
    <meta property="og:image" content="path/to/image.jpg">
    <link rel="icon" href="img/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon-180x180.png">
    <!-- Template Basic Images End -->

    <!-- Custom Browsers Color Start -->
    <meta name="theme-color" content="#000">
    <!-- Custom Browsers Color End -->

    <!-- Scripts -->
    <script src="{{ env('APP_URL') }}{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ env('APP_URL') }}{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ env('APP_URL') }}{{ mix('js/portal.js') }}" defer></script>
    <script src="{{ env('APP_URL') }}{{ mix('js/portal.animations.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ env('APP_URL') }}{{ mix('css/portal.css') }}" rel="stylesheet">
    <link href="{{ env('APP_URL') }}{{ mix('css/portal.animations.css') }}" rel="stylesheet">
    <link href="{{ env('APP_URL') }}{{ mix('css/fonts.css') }}" rel="stylesheet">
    <link href="{{ env('APP_URL') }}{{ mix('css/main.css') }}" rel="stylesheet">
</head>
<body>
<div id="portal">
    <div class="container-fluid site-cont">

        @include('portal.header')

        @yield('content')

        @include('portal.footer')

    </div>
</div>
@yield('scripts')
</body>
</html>
