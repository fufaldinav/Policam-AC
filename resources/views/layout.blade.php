<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ env('APP_URL') }}{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ env('APP_URL') }}{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ env('APP_URL') }}{{ mix('js/app.js') }}" defer></script>
    <script src="https://www.gstatic.com/firebasejs/5.8.5/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.8.5/firebase-messaging.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ env('APP_URL') }}{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ env('APP_URL') }}{{ mix('css/style.css') }}" rel="stylesheet">
</head>
<body>
<div id="container">
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')
</div>
<script>
    window.translations = {!! Cache::get('translations') !!};
</script>
</body>
</html>
