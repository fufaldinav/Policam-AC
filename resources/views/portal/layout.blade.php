<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <base href="/">

    <title>POLICAM | Главная</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Template Basic Images Start -->
    <meta property="og:image" content="path/to/image.jpg">
    <link rel="icon" href="img/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon-180x180.png">
    <!-- Template Basic Images End -->

    <!-- Custom Browsers Color Start -->
    <meta name="theme-color" content="#000">
    <!-- Custom Browsers Color End -->

    <link rel="stylesheet" href="css/portal.css">
    <link rel="stylesheet" href="css/portal-main.css">
    <link rel="stylesheet" href="libs/fontAwesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="libs/owlCarousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="libs/owlCarousel/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="libs/animate/animate.css">

</head>
<body>
<!-- Main Container Start -->
<div class="container-fluid site-cont">

    <!-- Header Start -->
    @include('portal.header')
    <!-- Header End -->

    @yield('content')

    <!-- Footer Start -->
    @include('portal.footer')
    <!-- Footer End -->


</div>
<!-- Main Container End -->

<script src="js/portal/scripts.min.js"></script>
<script src="libs/jquery/jquery-3.3.1.min.js"></script>
<script src="libs/wowJs/wow.min.js"></script>
<script src="libs/owlCarousel/js/owl.carousel.min.js"></script>
<script>
    new WOW().init();
</script>
<script>
    $("body").on('click', '[href*="#"]', function (e) {
        var fixed_offset = 100;
        $('html,body').stop().animate({scrollTop: $(this.hash).offset().top - fixed_offset}, 1000);
        e.preventDefault();
    });
</script>
<script>
    $(document).ready(function () {
        // $(".owl-products").owlCarousel({
        // 	loop: false,
        // 	margin: 10,
        // 	nav: true,
        // 	pagination : false,
        // 	dots: false,
        // 	navText: ['<div class="nav-slider-left"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>','<div class="nav-slider-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>'],
        // 	responsive: {
        // 		0: {
        // 			items: 1
        // 		}
        // 	}
        // });
        $(".owl-history").owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            pagination: false,
            dots: false,
            navText: ['<div class="nav-slider-left"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>', '<div class="nav-slider-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>'],
            responsive: {
                0: {
                    items: 1
                }
            }
        });
        $(".owl-news").owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            pagination: false,
            dots: false,
            navText: ['<div class="nav-slider-left"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>', '<div class="nav-slider-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>'],
            responsive: {
                0: {
                    items: 1
                }
            }
        });
        $(".owl-carousel").owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            pagination: false,
            dots: false,
            navText: ['<div class="nav-slider-left"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>', '<div class="nav-slider-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>'],
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                768: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });
    });
</script>

</body>
</html>
