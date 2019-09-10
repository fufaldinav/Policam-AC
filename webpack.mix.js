const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/ac.js', 'public/js')
    .js('resources/js/portal.js', 'public/js')
    .extract()
    .scripts([
        'resources/js/ac/notification.js',
        'resources/js/ac/push.js',
    ], 'public/js/scripts.js')
    .scripts([
        'resources/libs/owlCarousel/js/owl.carousel.js',
        'resources/libs/wowJs/wow.min.js',
        'resources/js/portal.animations.js',
    ], 'public/js/portal.animations.js')
    .sass('resources/sass/ac.scss', 'public/css')
    .sass('resources/sass/portal.scss', 'public/css')
    .styles([
        'resources/libs/animate/animate.css',
        'resources/libs/owlCarousel/css/owl.carousel.min.css',
        'resources/libs/owlCarousel/css/owl.theme.default.min.css',
    ], 'public/css/portal.animations.css')
    .styles([
        'resources/css/main.css',
    ], 'public/css/main.css')
    .sass('resources/libs/fontAwesome/scss/font-awesome.scss', 'public/css/fonts.css')
    .version()
    .disableNotifications()
    .browserSync('192.168.88.8');
