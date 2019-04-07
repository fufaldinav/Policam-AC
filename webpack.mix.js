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

mix.js('resources/js/app.js', 'public/js')
    .extract([
        'axios', 'bootstrap', 'jquery', 'lodash', 'laravel-echo',
        'popper.js', 'pusher-js', 'vue',
    ])
    .scripts([
        'resources/js/ac/notification.js',
        'resources/js/ac/tree.js',
    ], 'public/js/scripts.js')
    .sass('resources/sass/app.scss', 'public/css')
    .styles([
        'resources/css/ac.css',
        'resources/css/edit_persons.css',
        'resources/css/notification.css',
        'resources/css/tables.css',
    ], 'public/css/style.css')
    .version()
    .browserSync('localhost')
    .disableNotifications();


