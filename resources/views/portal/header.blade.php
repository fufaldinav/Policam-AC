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
                            <a href="{{ route('pages.entry', ['id' => 10]) }}">ВАКАНСИИ</a>
                        </li>
                        <li class="header-mobile-menu-item dropdown">
                            <a class="dropdown-toggle" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                УСЛУГИ
                            </a>
                            <div class="dropdown-menu bg-white rounded-0" aria-labelledby="servicesDropdown">
                                <a class="dropdown-item border-0" href="{{ route('pages.entry', ['id' => 7]) }}">Видеонаблюдение</a>
                                <a class="dropdown-item border-0" href="{{ route('pages.entry', ['id' => 8]) }}">СКУД</a>
                                <a class="dropdown-item border-0" href="{{ route('pages.entry', ['id' => 9]) }}">Разработка ПО</a>
                            </div>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="{{ route('portal.contacts') }}">КОНТАКТЫ</a>
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
                    <a href="{{ route('pages.entry', ['id' => 10]) }}">ВАКАНСИИ</a>
                </li>
                <li class="header-menu-item dropdown">
                    <a class="dropdown-toggle" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        УСЛУГИ
                    </a>
                    <div class="dropdown-menu bg-primary rounded-0" aria-labelledby="servicesDropdown">
                        <a class="dropdown-item" href="{{ route('pages.entry', ['id' => 7]) }}">Видеонаблюдение</a>
                        <a class="dropdown-item" href="{{ route('pages.entry', ['id' => 8]) }}">СКУД</a>
                        <a class="dropdown-item" href="{{ route('pages.entry', ['id' => 9]) }}">Разработка ПО</a>
                    </div>
                </li>
                <li class="header-menu-item">
                    <a href="{{ route('portal.contacts') }}">КОНТАКТЫ</a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ route('portal.news') }}">НОВОСТИ</a>
                </li>
                <li class="header-menu-main header-menu-main-1">
                    <a href="mailto:info@policam.ru" target="_blank">
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
