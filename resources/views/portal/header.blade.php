<div class="section-main">
    <header>
        <div class="container">
            <div class="header-catalog">
                <div class="header-mobile-menu">
                    <ul>
                        <li class="header-mobile-menu-item">
                            <a href="#mainProducts">РЕШЕНИЯ</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="#mainHistory">ИСТОРИЯ&nbsp;УСПЕХА</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="{{ route('portal.services') }}">УСЛУГИ</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="{{ route('portal.prices') }}">ЦЕНЫ</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="{{ route('portal.support') }}">ПОДДЕРЖКА</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="#mainClients">ПАРТНЁРАМ</a>
                        </li>
                        <li class="header-mobile-menu-item">
                            <a href="{{ route('portal.news') }}">НОВОСТИ</a>
                        </li>
                    </ul>
                </div>
                <div class="header-catalog-content">
                    <div class="row">
                        <div class="col-sm-3">
                            <a href="#">
                                <div class="header-catalog-item">
                                    <img src="../img/templates/main-products/main-product-5.png">
                                    <p>ГОСУДАРСТВЕННЫЕ СТРУКТУРЫ</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="header-catalog-item">
                                    <img src="../img/templates/main-products/main-product-6.png">
                                    <p>ТРАНСПОРТ И ЛОГИСТИКА</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <div class="header-catalog-item">
                                <img src="../img/templates/main-products/main-product-2.png">
                                <p>ОБРАЗОВАТЕЛЬНЫЕ УЧРЕЖДЕНИЯ</p>
                            </div>
                            <div class="header-catalog-item">
                                <img src="../img/templates/main-products/main-product-4.png">
                                <p>ПРОИЗВОДСТВЕННЫЕ КОМПАНИИ</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="header-catalog-item">
                                <img src="../img/templates/main-products/main-product-1.png">
                                <p>ЖИЛЫЕ КОМПЛЕКСЫ</p>
                            </div>
                            <div class="header-catalog-item">
                                <img src="../img/templates/main-products/main-product-7.png">
                                <p>ЧАСТНЫЕ ДОМОВЛАДЕНИЯ</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="header-catalog-item">
                                <img src="../img/templates/main-products/main-product-8.png">
                                <p>РОЗНИЧНАЯ ТОРГОВЛЯ</p>
                            </div>
                            <div class="header-catalog-item">
                                <img src="../img/templates/main-products/main-product-3.png">
                                <p>ЗДРАВООХРАНЕНИЕ</p>
                            </div>
                        </div>
                    </div>
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
                    <a href="#mainProducts">РЕШЕНИЯ</a>
                </li>
                <li class="header-menu-item">
                    <a href="#mainHistory">ИСТОРИЯ&nbsp;УСПЕХА</a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ route('portal.services') }}">УСЛУГИ</a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ route('portal.prices') }}">ЦЕНЫ</a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ route('portal.support') }}">ПОДДЕРЖКА</a>
                </li>
                <li class="header-menu-item">
                    <a href="#mainClients">ПАРТНЁРАМ</a>
                </li>
                <li class="header-menu-item">
                    <a href="{{ route('portal.news') }}">НОВОСТИ</a>
                </li>
                <li class="header-menu-main header-menu-main-1">
                    <a href="mailto:ixbit@mail.ru" target="_blank">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="header-menu-main header-menu-main-2">
                    <a href="#">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </a>
                </li>
                @auth
                    <li class="header-menu-item">
                        <a href="{{ route('cp.index') }}">
                            {{ Auth::user()->name }}
                        </a>
                    </li>
                @else
                    <li class="header-menu-main header-menu-main-3">
                        <a href="{{ route('cp.index') }}">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </header>
</div>
