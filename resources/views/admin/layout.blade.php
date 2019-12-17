<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Scripts -->
    <script src="{{ env('APP_URL') }}{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ env('APP_URL') }}{{ mix('js/vendor.js') }}" defer></script>
    <script src="{{ env('APP_URL') }}{{ mix('js/admin.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ env('APP_URL') }}{{ mix('css/ac.css') }}" rel="stylesheet">
</head>
<body>
<div id="admin">
    <div class="navbar navbar-expand-sm navbar-light sticky-top px-0 bg-white ac-navbar">
        <div class="container-fluid">
            <a class="navbar-brand ml-3" href="{{ url('/') }}">
                <img src="/img/logo-policam.png" alt="">
            </a>
            <button class="navbar-toggler mr-3" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Переключить навигацию') }}"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse px-2 bg-white" id="navbarSupportedContent">
                <!-- Right Side Of Navbar -->
                <div class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <a class="nav-item nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                        @if (Route::has('register'))
                            <a class="nav-item nav-link"
                               href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdownMenuLink"
                               data-toggle="dropdown" data-display="static" aria-haspopup="true"
                               aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right m-2 px-2"
                                 aria-labelledby="userDropdownMenuLink">
                                <a class="nav-item nav-link" href="{{ route('cp.index') }}">
                                    {{ __('Панель управления') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="nav-item nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Выход') }}
                                </a>
                            </div>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>
    @yield('content')
</div>
<script>
    @yield('scripts')
</script>
</body>
</html>
