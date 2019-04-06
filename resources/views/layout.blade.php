<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ url("css/ac.css") }}?{{ time() }}"/>
    @isset($css_list)
        @foreach ($css_list as $css)
            <link rel="stylesheet" href="{{ url("css/$css.css") }}?{{ time() }}"/>
        @endforeach
    @endisset
    <script src="{{ env('APP_URL') }}{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ env('APP_URL') }}{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ env('APP_URL') }}{{ mix('js/app.js') }}" defer></script>
    <script src="https://www.gstatic.com/firebasejs/5.8.5/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.8.5/firebase-messaging.js"></script>
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
