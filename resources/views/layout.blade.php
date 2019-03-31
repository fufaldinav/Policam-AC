<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ Session::token() }}">
    <link rel="stylesheet" href="{{ url("css/ac.css") }}?{{ time() }}"/>
    @isset($css_list)
        @foreach ($css_list as $css)
            <link rel="stylesheet" href="{{ url("css/$css.css") }}?{{ time() }}"/>
        @endforeach
    @endisset
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.8.5/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.8.5/firebase-messaging.js"></script>
    @isset($js_list)
        @foreach ($js_list as $js)
            @include('js.' . $js)
        @endforeach
    @endisset
</head>
<body>
<div id="container">
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')
</div>
</body>
</html>
