new WOW().init();

$("body").on('click', '[href*="#"]', function (e) {
    var fixed_offset = 100;
    $('html,body').stop().animate({scrollTop: $(this.hash).offset().top - fixed_offset}, 1000);
    e.preventDefault();
});

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
        autoplay: true,
        autoplayTimeout: 2000,
        autoplayHoverPause: true,
        loop: true,
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


$('.menu-btn').on('click', function (e) {
    e.preventDefault();
    $(this).toggleClass('menu-btn_active');
    $('.header-catalog').toggleClass('header-catalog_active');
});

let video = document.getElementById('video-banner')
const promise = video.play()

if (promise !== null) {
    promise.catch(() => video.play())
}

$(document).ready(function () {
    $(".main-products-item-1").click(function () {
        if ($(".main-product-item-1").is(":hidden")) {
            $(".main-product-item-1").show("slow");
            $(".main-product-item-2").hide("slow");
            $(".main-product-item-3").hide("slow");
            $(".main-product-item-4").hide("slow");
            $(".main-product-item-5").hide("slow");
            $(".main-product-item-6").hide("slow");
            $(".main-product-item-7").hide("slow");
        }
        return false;
    });
    $(".main-products-item-2").click(function () {
        if ($(".main-product-item-2").is(":hidden")) {
            $(".main-product-item-2").show("slow");
            $(".main-product-item-1").hide("slow");
            $(".main-product-item-3").hide("slow");
            $(".main-product-item-4").hide("slow");
            $(".main-product-item-5").hide("slow");
            $(".main-product-item-6").hide("slow");
            $(".main-product-item-7").hide("slow");
        }
        return false;
    });
    $(".main-products-item-3").click(function () {
        if ($(".main-product-item-3").is(":hidden")) {
            $(".main-product-item-3").show("slow");
            $(".main-product-item-1").hide("slow");
            $(".main-product-item-2").hide("slow");
            $(".main-product-item-4").hide("slow");
            $(".main-product-item-5").hide("slow");
            $(".main-product-item-6").hide("slow");
            $(".main-product-item-7").hide("slow");
        }
        return false;
    });
    $(".main-products-item-4").click(function () {
        if ($(".main-product-item-4").is(":hidden")) {
            $(".main-product-item-4").show("slow");
            $(".main-product-item-1").hide("slow");
            $(".main-product-item-2").hide("slow");
            $(".main-product-item-3").hide("slow");
            $(".main-product-item-5").hide("slow");
            $(".main-product-item-6").hide("slow");
            $(".main-product-item-7").hide("slow");
        }
        return false;
    });
    $(".main-products-item-5").click(function () {
        if ($(".main-product-item-5").is(":hidden")) {
            $(".main-product-item-5").show("slow");
            $(".main-product-item-1").hide("slow");
            $(".main-product-item-2").hide("slow");
            $(".main-product-item-3").hide("slow");
            $(".main-product-item-4").hide("slow");
            $(".main-product-item-6").hide("slow");
            $(".main-product-item-7").hide("slow");
        }
        return false;
    });
    $(".main-products-item-6").click(function () {
        if ($(".main-product-item-6").is(":hidden")) {
            $(".main-product-item-6").show("slow");
            $(".main-product-item-1").hide("slow");
            $(".main-product-item-2").hide("slow");
            $(".main-product-item-3").hide("slow");
            $(".main-product-item-4").hide("slow");
            $(".main-product-item-5").hide("slow");
            $(".main-product-item-7").hide("slow");
        }
        return false;
    });
    $(".main-products-item-7").click(function () {
        if ($(".main-product-item-7").is(":hidden")) {
            $(".main-product-item-7").show("slow");
            $(".main-product-item-1").hide("slow");
            $(".main-product-item-2").hide("slow");
            $(".main-product-item-3").hide("slow");
            $(".main-product-item-4").hide("slow");
            $(".main-product-item-5").hide("slow");
            $(".main-product-item-6").hide("slow");
        }
        return false;
    });
});
