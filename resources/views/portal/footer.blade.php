<!-- Footer Start -->
<div class="section-main">
    <footer>
        <div class="container">
            <div class="row justify-content-center footer-content">
                <div class="col-sm-3">
                    <div class="footer-logo">
                        <a href="/">
                            <img src="../img/logo.png" alt="Лого POLICAM">
                        </a>
                    </div>
                    <ul class="footer-links">
                        <li>
                            <a href="{{ route('portal.contacts') }}">О компании</a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}#mainProducts">Продукты</a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}#mainProducts">Решения</a>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Услуги
                            </a>
                            <div class="dropdown-menu rounded-0" aria-labelledby="servicesDropdown" style="background-color: #003854;">
                                <a class="dropdown-item" href="{{ route('pages.entry', ['id' => 7]) }}">Видеонаблюдение</a>
                                <a class="dropdown-item" href="{{ route('pages.entry', ['id' => 8]) }}">СКУД</a>
                                <a class="dropdown-item" href="{{ route('pages.entry', ['id' => 9]) }}">Разработка ПО</a>
                            </div>
                        </li>
                        <li>
                            <a href="{{ url('/') }}#mainProducts">История успеха</a>
                        </li>
                        <li>
                            <a href="{{ route('portal.support') }}">Поддержка</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <ul class="footer-links">
                        <li>
                            <a href="{{ route('pages.entry', ['id' => 1]) }}">Система контроля и управления доступом</a>
                        </li>
                        <li>
                            <a href="{{ route('pages.entry', ['id' => 2]) }}">Системы видеонаблюдения и видеорегистраторы</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <ul class="footer-links">
                        <li>
                            <a href="{{ route('pages.entry', ['id' => 4]) }}">Спутниковой мониторинг ГЛОНАСС</a>
                        </li>
                        <li>
                            <a href="{{ route('pages.entry', ['id' => 5]) }}">Умный дом</a>
                        </li>
                        <li>
                            <a href="{{ route('pages.entry', ['id' => 6]) }}">Охрана периметра</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <div class="footer-contact-info">
                        <div class="footer-contact-address">
                            <p>Свердловская обл.</p>
                            <p>г. Заречный</p>
                            <p>ул. Мира, 40</p>
                        </div>
                        <div class="footer-contact-phone">
                            <a href="tel:+73437778887">
                                <p>+ 7 (343) 777 888 7</p>
                            </a>
                        </div>
                    </div>
                    <div class="footer-soc-line"></div>
                    <div class="footer-soc">
                        <a class="social-item" href="https://vk.com/policam66" target="_blank">
                            <i class="fa fa-vk" aria-hidden="true"></i>
                        </a>
                        <a class="social-item" href="mailto:ixbit@mail.ru" target="_blank">
                            <i class="fa fa-at" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div style="background-color: #104A64; margin: 20px 0; width: 100%; height: 1px;"></div>
                <p class="footer-policy">Вся размещенная на сайте информация носит информационно-справочный характер
                    и не является публичной офертой согласно действующему законодательству РФ.<br>&copy; Поликам, 2019 г.</p>
            </div>
        </div>
    </footer>
</div>
<!-- Footer End -->
