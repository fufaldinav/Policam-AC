<!-- Header Start -->
<div class="section-main">
    <header>
        <div class="container">
            <div class="header-catalog">
                <div class="header-mobile-menu">
                    <ul>
                        <li class="header-mobile-menu-item">
                            <a href="{{ url('/') }}#mainProducts">РЕШЕНИЯ</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="{{ url('/') }}#mainHistory">ИСТОРИЯ УСПЕХА</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="{{ route('portal.services') }}">УСЛУГИ</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="{{ route('portal.support') }}">ПОДДЕРЖКА</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="{{ route('portal.news') }}">НОВОСТИ</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <nav class="header-nav">
            <ul>
                <li class="header-menu-btn">
                        <span class="menu-btn">
                        <span></span>
                    </span>
                </li>
                <li class="header-logo">
                    <a href="/">
                        <img src="../img/logo.png" alt="Лого POLICAM">
                    </a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ url('/') }}#mainProducts">РЕШЕНИЯ</a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ url('/') }}#mainHistory">ИСТОРИЯ УСПЕХА</a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ route('portal.services') }}">УСЛУГИ</a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ route('portal.support') }}">ПОДДЕРЖКА</a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ route('portal.news') }}">НОВОСТИ</a>
                </li>
                <li class="header-menu-main header-menu-main-1">
                    <a href="mailto:ixbit@mail.ru" target="_blank">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    </a>
                </li>
{{--                <li class="header-menu-main header-menu-main-2">--}}
{{--                    <a href="#">--}}
{{--                        <i class="fa fa-search" aria-hidden="true"></i>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="header-menu-main header-menu-main-2">
                    <a href="{{ route('cp.index') }}">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        @auth
                            {{ Auth::user()->name }}
                        @else
                            {{ __('Вход') }}
                        @endauth
                    </a>
                </li>
            </ul>
        </nav>
    </header>
</div>
<!-- Header End -->
