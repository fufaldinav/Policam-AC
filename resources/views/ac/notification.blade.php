<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="{{ url("css/notification.css") }}?{{ time() }}"/>
    @include('js.notification')
</head>
<body>
<button class="controls controls-left" id="previous"><</button>
<button class="controls controls-right" id="next">></button>
<ul id="slides">
    @foreach($photos as $photo)
        <li class="slide">
            <img src="{{ str_replace(config('ac.camera_path'), '/img/snapshots', $photo) }}" class="slideimg">
        </li>
    @endforeach
</ul>
</body>
</html>
