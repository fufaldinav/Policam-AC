new WOW().init();

// $("body").on('click', '[href*="#"]', function (e) {
//     var fixed_offset = 100;
//     $('html,body').stop().animate({scrollTop: $(this.hash).offset().top - fixed_offset}, 1000);
//     e.preventDefault();
// });

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
