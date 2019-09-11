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
                <p>PSIM. Системы контроля<br>и управления доступом<br>и видеонаблюдение.</p>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Products Start -->
    <portal-products-content></portal-products-content>
    <!-- Products End -->

    <!-- News Start -->
    <div class="section">
        <div class="main-news" id="mainNews">
            <h2 class="wow fadeInUp" data-wow-duration="1s">НОВОСТИ</h2>
            <div class="title-line"></div>
            <div class="main-news-content">
                <div class="container" style="width: 100%;">
                    <div class="row">
                        <div class="col-sm-12 col-md-8 col-xl-9 main-news-1">
                            <div class="main-news-media wow fadeInLeft" data-wow-duration="2s">
                                <div class="main-news-media-text">
                                    <p class="main-news-media-text-name">Пресса о нас</p>
                                    <h3 class="main-news-media-text-title">Компания Policam не стоит не месте</h3>
                                    <div class="banner-line"></div>
                                    <p class="main-news-media-text-content">Компания Поликам - одна из тех молодых
                                        развивающихся компаний, которые уже сегодня выполняют сложные проекты для
                                        реализации программ "Безопасный город" и "Умный город".
                                        Используя доступные программные разработки крупных мировых IT компаний, создают
                                        готовые к интеграции PSIM решения.
                                    </p>
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
                                            <p class="main-news-theme-day-text-p">{{ $lastNews[0]->header }}</p>
                                            <p class="main-news-theme-day-text-date">{{ $lastNews[0]->formattedDate('d/m/Y') }}</p>
                                            <a href="{{ route('portal.news.entry', ['id' => $lastNews[0]->id]) }}"
                                               class="main-news-theme-day-text-link">Читать
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
                                    <h3 class="main-news-theme-day-news-title">«Ростелеком» купил создателя
                                        «Безопасного города», принадлежавшего «Техносерву» и «Мегафону»</h3>
                                    <a href="{{ route('portal.news.entry', ['id' => 4]) }}"
                                       class="main-news-theme-day-news-link">Читать
                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-xl-3 main-news-2 wow fadeInRight" data-wow-duration="2s">
                            <div class="main-other-news">
                                <h3 class="main-other-news-name">Другие новости</h3>
                                @foreach($lastNews as $entry)
                                    <div class="main-other-news-item">
                                        <a href="{{ route('portal.news.entry', ['id' => $entry->id]) }}">
                                            <h4>{{ $entry->header }}</h4>
                                            <p>{{ $entry->formattedDate('d/m/Y') }}</p>
                                        </a>
                                    </div>
                                @endforeach
                                <span class="main-button-link">
                                <a href="{{ route('portal.news') }}">Все новости</a>
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
    <portal-history></portal-history>
    <!-- History End -->

    <!-- Clients Start -->
    <portal-clients></portal-clients>
    <!-- Clients End -->

    <!-- Partners Start -->
    <portal-partners></portal-partners>
    <!-- Partners End -->
@endsection
