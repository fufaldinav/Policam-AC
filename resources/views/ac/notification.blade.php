<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}?{{ time() }}"/>
    <script type="text/javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.touchSwipe.min.js') }}"></script>
    @include('js.notification')
</head>
<body>
<button class="button controls controls-left" id="previous">Назад</button>
<button class="button controls controls-right" id="next">Вперёд</button>
<ul id="slides">
    @foreach($photos as $photo)
        @if ($loop->first)
            @php($class = "slide showing")
        @else
            @php($class = "slide")
        @endif
        <li class="{{ $class }}">
            <img src="{{ str_replace(config('ac.camera_path'), '/img/snapshots', $photo) }}" class="slideimg">
        </li>
    @endforeach
</ul>
</body>
</html>
