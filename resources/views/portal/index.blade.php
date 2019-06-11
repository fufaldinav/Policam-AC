@extends('portal.layout')

@section('content')
<!-- Banner Start -->
<div class="section-main">
    <div class="main-banner">
        <!-- <img src="../img/templates/main-banner/video-screen.jpg" alt="" class="video-banner"> -->
        <video id="video-banner" src="../img/templates/main-banner/video-banner.mp4" autoplay loop
               class="video-banner"></video>
        <img src="../img/templates/main-banner/video-screen.jpg" class="video-screen">
        <div class="banner-title">
            <h1>ИНТЕГРАЦИЯ<br>СОВРЕМЕННЫХ<br>ТЕХНОЛОГИЙ</h1>
            <div class="row justify-content-center">
                <div class="banner-line"></div>
            </div>
            <p>Распознавание голоса, синтез речи,<br>запись и анализ, идентификация лица<br>и голоса.</p>
        </div>
    </div>
</div>
<!-- Banner End -->

<!-- Products Start -->
<div class="section-main">
    <div class="main-products" id="mainProducts">
        <div class="container">
            <div class="main-products-header wow fadeInUp" data-wow-duration="2s">
                <ul class="main-products-items">
                    <li class="main-products-item main-products-item-1">
                        <div class="main-products-item-link">
                            <img src="../img/templates/main-products/main-product-5.png" alt="Продукт 5">
                        </div>
                    </li>
                    <li class="main-products-item">
                        <img src="../img/templates/main-products/main-product-line.png"
                             class="main-products-item-line">
                    </li>
                    <li class="main-products-item main-products-item-2">
                        <div class="main-products-item-link">
                            <img src="../img/templates/main-products/main-product-2.png" alt="Продукт 2">
                        </div>
                    </li>
                    <li class="main-products-item">
                        <img src="../img/templates/main-products/main-product-line.png"
                             class="main-products-item-line">
                    </li>
                    <li class="main-products-item main-products-item-3">
                        <div class="main-products-item-link">
                            <img src="../img/templates/main-products/main-product-1.png" alt="Продукт 1">
                        </div>
                    </li>
                    <li class="main-products-item">
                        <img src="../img/templates/main-products/main-product-line.png"
                             class="main-products-item-line">
                    </li>
                    <li class="main-products-item main-products-item-4">
                        <div class="main-products-item-link">
                            <img src="../img/templates/main-products/main-product-8.png" alt="Продукт 8">
                        </div>
                    </li>
                    <li class="main-products-item">
                        <img src="../img/templates/main-products/main-product-line.png"
                             class="main-products-item-line">
                    </li>
                    <li class="main-products-item main-products-item-5">
                        <div class="main-products-item-link">
                            <img src="../img/templates/main-products/main-product-6.png" alt="Продукт 6">
                        </div>
                    </li>
                    <li class="main-products-item">
                        <img src="../img/templates/main-products/main-product-line.png"
                             class="main-products-item-line">
                    </li>
                    <li class="main-products-item main-products-item-6">
                        <div class="main-products-item-link">
                            <img src="../img/templates/main-products/main-product-4.png" alt="Продукт 4">
                        </div>
                    </li>
                    <li class="main-products-item">
                        <img src="../img/templates/main-products/main-product-line.png"
                             class="main-products-item-line">
                    </li>
                    <li class="main-products-item main-products-item-7">
                        <div class="main-products-item-link">
                            <img src="../img/templates/main-products/main-product-7.png" alt="Продукт 7">
                        </div>
                    </li>
                    <li class="main-products-item">
                        <img src="../img/templates/main-products/main-product-line.png"
                             class="main-products-item-line">
                    </li>
                    <li class="main-products-item main-products-item-8">
                        <div class="main-products-item-link">
                            <img src="../img/templates/main-products/main-product-3.png" alt="Продукт 3">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-products-content">
            <img src="../img/templates/main-products/main-products-bg-second.png"
                 class="main-products-bg-second wow fadeIn" data-wow-duration="2s">
            <div class="container">
                <div class="main-product-info wow fadeInRight" data-wow-duration="2s">
                    <div class="main-product-item main-product-item-1">
                        <div class="row main-product-item-content">
                            <div class="col-md-6">
                                <img src="../img/templates/main-products/main-products-bg.png"
                                     class="main-products-bg wow fadeIn" data-wow-duration="2s">
                            </div>
                            <div class="col-md-6 main-product-item-content-txt">
                                <p class="main-product-small-title">Готовые решения</p>
                                <h3 class="main-product-title">ГОСУДАРСТВЕННЫЕ<br>СТРУКТУРЫ</h3>
                                <div class="main-product-line"></div>
                                <p class="main-product-text">Будет ли техника восхищать - вопрос разумного выбора
                                    компонентов, а главное - профессиональной установки, причем именно в вашем доме,
                                    а не демозале салона, где «все и всегда звучит».</p>
                                <span class="main-button-link">
											<a href="#">Смотреть готовые решения</a>
										</span>
                            </div>
                        </div>
                    </div>
                    <div class="main-product-item main-product-item-2">
                        <div class="row main-product-item-content">
                            <div class="col-md-6">
                                <img src="../img/templates/main-products/main-products-bg.png"
                                     class="main-products-bg wow fadeIn" data-wow-duration="2s">
                            </div>
                            <div class="col-md-6 main-product-item-content-txt">
                                <p class="main-product-small-title">Готовые решения</p>
                                <h3 class="main-product-title">ОБРАЗОВАТЕЛЬНЫЕ<br>УЧРЕЖДЕНИЯ</h3>
                                <div class="main-product-line"></div>
                                <p class="main-product-text">Будет ли техника восхищать - вопрос разумного выбора
                                    компонентов, а главное - профессиональной установки, причем именно в вашем доме,
                                    а не демозале салона, где «все и всегда звучит».</p>
                                <span class="main-button-link">
											<a href="#">Смотреть готовые решения</a>
										</span>
                            </div>
                        </div>
                    </div>
                    <div class="main-product-item main-product-item-3">
                        <div class="row main-product-item-content">
                            <div class="col-md-6">
                                <img src="../img/templates/main-products/main-products-bg.png"
                                     class="main-products-bg wow fadeIn" data-wow-duration="2s">
                            </div>
                            <div class="col-md-6 main-product-item-content-txt">
                                <p class="main-product-small-title">Готовые решения</p>
                                <h3 class="main-product-title">ЖИЛЫЕ<br>КОМПЛЕКСЫ</h3>
                                <div class="main-product-line"></div>
                                <p class="main-product-text">Будет ли техника восхищать - вопрос разумного выбора
                                    компонентов, а главное - профессиональной установки, причем именно в вашем доме,
                                    а не демозале салона, где «все и всегда звучит».</p>
                                <span class="main-button-link">
											<a href="#">Смотреть готовые решения</a>
										</span>
                            </div>
                        </div>
                    </div>
                    <div class="main-product-item main-product-item-4">
                        <div class="row main-product-item-content">
                            <div class="col-md-6">
                                <img src="../img/templates/main-products/main-products-bg.png"
                                     class="main-products-bg wow fadeIn" data-wow-duration="2s">
                            </div>
                            <div class="col-md-6 main-product-item-content-txt">
                                <p class="main-product-small-title">Готовые решения</p>
                                <h3 class="main-product-title">РОЗНИЧНАЯ<br>ТОРГОВЛЯ</h3>
                                <div class="main-product-line"></div>
                                <p class="main-product-text">Будет ли техника восхищать - вопрос разумного выбора
                                    компонентов, а главное - профессиональной установки, причем именно в вашем доме,
                                    а не демозале салона, где «все и всегда звучит».</p>
                                <span class="main-button-link">
											<a href="#">Смотреть готовые решения</a>
										</span>
                            </div>
                        </div>
                    </div>
                    <div class="main-product-item main-product-item-5">
                        <div class="row main-product-item-content">
                            <div class="col-md-6">
                                <img src="../img/templates/main-products/main-products-bg.png"
                                     class="main-products-bg wow fadeIn" data-wow-duration="2s">
                            </div>
                            <div class="col-md-6 main-product-item-content-txt">
                                <p class="main-product-small-title">Готовые решения</p>
                                <h3 class="main-product-title">ТРАНСПОРТ<br>И<br>ЛОГИСТИКА</h3>
                                <div class="main-product-line"></div>
                                <p class="main-product-text">Будет ли техника восхищать - вопрос разумного выбора
                                    компонентов, а главное - профессиональной установки, причем именно в вашем доме,
                                    а не демозале салона, где «все и всегда звучит».</p>
                                <span class="main-button-link">
											<a href="#">Смотреть готовые решения</a>
										</span>
                            </div>
                        </div>
                    </div>
                    <div class="main-product-item main-product-item-6">
                        <div class="row main-product-item-content">
                            <div class="col-md-6">
                                <img src="../img/templates/main-products/main-products-bg.png"
                                     class="main-products-bg wow fadeIn" data-wow-duration="2s">
                            </div>
                            <div class="col-md-6 main-product-item-content-txt">
                                <p class="main-product-small-title">Готовые решения</p>
                                <h3 class="main-product-title">ПРОИЗВОДСТВЕННЫЕ<br>КОМПАНИИ</h3>
                                <div class="main-product-line"></div>
                                <p class="main-product-text">Будет ли техника восхищать - вопрос разумного выбора
                                    компонентов, а главное - профессиональной установки, причем именно в вашем доме,
                                    а не демозале салона, где «все и всегда звучит».</p>
                                <span class="main-button-link">
											<a href="#">Смотреть готовые решения</a>
										</span>
                            </div>
                        </div>
                    </div>
                    <div class="main-product-item main-product-item-7">
                        <div class="row main-product-item-content">
                            <div class="col-md-6">
                                <img src="../img/templates/main-products/main-products-bg.png"
                                     class="main-products-bg wow fadeIn" data-wow-duration="2s">
                            </div>
                            <div class="col-md-6 main-product-item-content-txt">
                                <p class="main-product-small-title">Готовые решения</p>
                                <h3 class="main-product-title">ЧАСТНЫЕ<br>ДОМОВЛАДЕНИЯ</h3>
                                <div class="main-product-line"></div>
                                <p class="main-product-text">Будет ли техника восхищать - вопрос разумного выбора
                                    компонентов, а главное - профессиональной установки, причем именно в вашем доме,
                                    а не демозале салона, где «все и всегда звучит».</p>
                                <span class="main-button-link">
											<a href="#">Смотреть готовые решения</a>
										</span>
                            </div>
                        </div>
                    </div>
                    <div class="main-product-item main-product-item-8">
                        <div class="row main-product-item-content">
                            <div class="col-md-6">
                                <img src="../img/templates/main-products/main-products-bg.png"
                                     class="main-products-bg wow fadeIn" data-wow-duration="2s">
                            </div>
                            <div class="col-md-6 main-product-item-content-txt">
                                <p class="main-product-small-title">Готовые решения</p>
                                <h3 class="main-product-title">ЗДРАВООХРАНЕНИЕ</h3>
                                <div class="main-product-line"></div>
                                <p class="main-product-text">Будет ли техника восхищать - вопрос разумного выбора
                                    компонентов, а главное - профессиональной установки, причем именно в вашем доме,
                                    а не демозале салона, где «все и всегда звучит».</p>
                                <span class="main-button-link">
											<a href="#">Смотреть готовые решения</a>
										</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Products End -->

<!-- News Start -->
<div class="section">
    <div class="main-news" id="mainNews">
        <h2 class="wow fadeInUp" data-wow-duration="1s">НОВОСТИ КОМПАНИИ</h2>
        <div class="title-line"></div>
        <div class="main-news-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-9 main-news-1">
                        <div class="main-news-media wow fadeInLeft" data-wow-duration="2s">
                            <div class="main-news-media-text">
                                <p class="main-news-media-text-name">Пресса о нас</p>
                                <h3 class="main-news-media-text-title">РБК+: «Биометрия – инструмент новых
                                    возможностей»</h3>
                                <div class="banner-line"></div>
                                <p class="main-news-media-text-content">Будет ли техника восхищать — вопрос
                                    разумного выбора компонентов, а главное — профессиональной установки, причем
                                    именно в вашем доме, а не демозале салона, где «все и всегда звучит».</p>
                                <div class="main-news-figure"></div>
                            </div>
                            <div class="main-news-media-img">
                                <img src="../img/templates/main-news/main-news-press-now.jpg" alt="">
                                <div class="main-news-figure-left"></div>
                            </div>
                        </div>
                        <div class="main-news-theme-day">
                            <div class="main-news-theme-day-text">
                                <div class="main-news-theme-day-text-img">
                                    <div class="img-overlay"></div>
                                    <div class="main-news-theme-day-text-content">
                                        <p class="main-news-theme-day-text-name">Тема дня</p>
                                        <p class="main-news-theme-day-text-p">РБК+: «Биометрия – инструмент новых
                                            возможностей»</p>
                                        <p class="main-news-theme-day-text-date">22 апреля 2019 г.</p>
                                        <a href="#" class="main-news-theme-day-text-link">Читать
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="main-news-theme-day-img">
                                <img src="../img/templates/main-news/main-news-theme-now.jpg" alt="">
                                <div class="main-news-figure-right"></div>
                            </div>
                            <div class="main-news-theme-day-news">
                                <p class="main-news-theme-day-news-name">Новости</p>
                                <h3 class="main-news-theme-day-news-title">Создатели системы распознавания лиц
                                    рассказали, возможно ли задержать болельщика Голунова в Туле</h3>
                                <a href="#" class="main-news-theme-day-news-link">Читать
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 main-news-2 wow fadeInRight" data-wow-duration="2s">
                        <div class="main-other-news">
                            <h3 class="main-other-news-name">Другие новости</h3>
                            <div class="main-other-news-item">
                                <a href="#">
                                    <h4>РБК+: «Биометрия – инструмент новых возможностей»</h4>
                                    <p>18 апреля 2019 г.</p>
                                </a>
                            </div>
                            <div class="main-other-news-item">
                                <a href="#">
                                    <h4>Сбербанк создал цифровую телеведущую новостей с голосом от ЦРТ</h4>
                                    <p>18 апреля 2019 г.</p>
                                </a>
                            </div>
                            <div class="main-other-news-item">
                                <a href="#">
                                    <h4>ТАСС: Система распознавания лиц не пустила болельщика на матч РПЛ «Арсенал»
                                        – «Урал»</h4>
                                    <p>18 апреля 2019 г.</p>
                                </a>
                            </div>
                            <div class="main-other-news-item main-other-news-item-end">
                                <a href="#">
                                    <h4>РБК+: «Биометрия – инструмент новых возможностей»</h4>
                                    <p>18 апреля 2019 г.</p>
                                </a>
                            </div>
                            <div class="main-other-news-item main-other-news-item-end">
                                <a href="#">
                                    <h4>Сбербанк создал цифровую телеведущую новостей с голосом от ЦРТ</h4>
                                    <p>18 апреля 2019 г.</p>
                                </a>
                            </div>
                            <span class="main-button-link">
										<a href="#">Все новости</a>
									</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- News End -->


<!-- History Start -->
<div class="section" style="padding-top: 0px;">
    <div class="main-history" id="mainHistory">
        <div class="main-history-bg"></div>
        <h2 class="wow fadeInUp" data-wow-duration="1s">ИСТОРИЯ УСПЕХА</h2>
        <div class="title-line"></div>
        <div class="main-history-content owl-carousel owl-history owl-theme owl-loaded">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-5">
                        <div class="main-history-item">
                            <img src="../img/templates/main-history/main-history-product.png" alt="">
                            <h3>Частная школа<br>ПРЕЗИДЕНТ</h3>
                            <p>Проанализировано 35,5 тысяч фонограмм телефонных переговоров операторов с клиентами с
                                помощью системы Smart Logger II, а также встроенного в систему модуля QM
                                Analyzer</p>
                            <div class="history-button">
										<span class="main-button-link">
											<a href="#">Узнать подробнее</a>
										</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-5">
                        <div class="main-history-item">
                            <img src="../img/templates/main-history/main-history-product.png" alt="">
                            <h3>Частная школа<br>ПРЕЗИДЕНТ</h3>
                            <p>Проанализировано 35,5 тысяч фонограмм телефонных переговоров операторов с клиентами с
                                помощью системы Smart Logger II, а также встроенного в систему модуля QM
                                Analyzer</p>
                            <div class="history-button">
										<span class="main-button-link">
											<a href="#">Узнать подробнее</a>
										</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-5">
                        <div class="main-history-item">
                            <img src="../img/templates/main-history/main-history-product.png" alt="">
                            <h3>Частная школа<br>ПРЕЗИДЕНТ</h3>
                            <p>Проанализировано 35,5 тысяч фонограмм телефонных переговоров операторов с клиентами с
                                помощью системы Smart Logger II, а также встроенного в систему модуля QM
                                Analyzer</p>
                            <div class="history-button">
										<span class="main-button-link">
											<a href="#">Узнать подробнее</a>
										</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-5">
                        <div class="main-history-item">
                            <img src="../img/templates/main-history/main-history-product.png" alt="">
                            <h3>Частная школа<br>ПРЕЗИДЕНТ</h3>
                            <p>Проанализировано 35,5 тысяч фонограмм телефонных переговоров операторов с клиентами с
                                помощью системы Smart Logger II, а также встроенного в систему модуля QM
                                Analyzer</p>
                            <div class="history-button">
										<span class="main-button-link">
											<a href="#">Узнать подробнее</a>
										</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-5">
                        <div class="main-history-item">
                            <img src="../img/templates/main-history/main-history-product.png" alt="">
                            <h3>Частная школа<br>ПРЕЗИДЕНТ</h3>
                            <p>Проанализировано 35,5 тысяч фонограмм телефонных переговоров операторов с клиентами с
                                помощью системы Smart Logger II, а также встроенного в систему модуля QM
                                Analyzer</p>
                            <div class="history-button">
										<span class="main-button-link">
											<a href="#">Узнать подробнее</a>
										</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- History End -->


<!-- Clients Start -->
<div class="section">
    <div class="main-clients" id="mainClients">
        <h2 class="wow fadeInUp" data-wow-duration="1s">НАШИ ПАРТНЁРЫ</h2>
        <div class="title-line"></div>
        <div class="container">
            <div class="main-clients-content owl-carousel owl-theme owl-loaded">
                <div class="main-clients-item">
                    <img src="../img/partners/dahua.jpg" alt="Dahua">
                </div>
                <div class="main-clients-item">
                    <img src="../img/partners/devline.jpg" alt="Devline">
                </div>
                <div class="main-clients-item">
                    <img src="../img/partners/hikvision.png" alt="Hikvision">
                </div>
                <div class="main-clients-item">
                    <img src="../img/partners/ironlogic.jpg" alt="IronLogic">
                </div>
                <div class="main-clients-item">
                    <img src="../img/partners/macroscop.png" alt="Macroscop">
                </div>
                <div class="main-clients-item">
                    <img src="../img/partners/sigur.jpg" alt="Sigur">
                </div>
                <div class="main-clients-item">
                    <img src="../img/partners/trassir.jpg" alt="Trassir">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Clients End -->
@endsection
