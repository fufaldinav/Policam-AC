<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
</head>
<body>
<div id="ac">
    <ac-layout>
        <template slot="header">
            <ac-nav-bar>
                <template slot="ac-logo">
                    <a class="navbar-brand ml-3" href="{{ url('/') }}">
                        <img src="/img/logo-policam.png" alt="">
                    </a>
                </template>
                <template slot="ac-organizations">
                    @auth
                        <ac-organizations-dropdown-menu></ac-organizations-dropdown-menu>
                    @endauth
                </template>
                <template slot="ac-menu-button">
                    <button class="navbar-toggler mr-3" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false"
                            aria-label="{{ __('Toggle navigation') }}"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </template>
                <template slot="ac-nav-left-side">
                    <div class="navbar-nav mr-auto">
                        @auth
                            <a class="nav-item nav-link" href="{{ route('observer') }}">{{ __('ac.observation') }}</a>
                            <a class="nav-item nav-link"
                               href="{{ route('cp.persons') }}">{{ __('ac.personal') }}</a>
                            {{--                            <a class="nav-item nav-link" href="{{ route('cp.classes') }}">{{ __('ac.classes') }}</a>--}}
                        @endauth
                    </div>
                </template>
                <template slot="ac-nav-right-side">
                    <div class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <a class="nav-item nav-link" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                            @if (Route::has('register'))
                                <a class="nav-item nav-link"
                                   href="{{ route('register') }}">{{ __('auth.register') }}</a>
                            @endif
                        @else
                            <a class="nav-item nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('auth.logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        @endguest
                    </div>
                </template>
            </ac-nav-bar>
        </template>
        <template slot="main">
            @yield('content')
        </template>
        <template slot="footer">
        </template>
    </ac-layout>
    <ac-alert :message="alertMessage" :type="alertType"></ac-alert>
</div>
<script>
    @yield('scripts')
</script>
</body>
</html>
