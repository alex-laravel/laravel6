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

mix
    .styles([
        'resources/assets/css/shared/shared.css',
        'resources/assets/css/frontend/frontend.css'
    ], 'public/css/frontend.css')
    .styles([
        'resources/assets/css/shared/shared.css',
        'resources/assets/css/backend/backend.css'
    ], 'public/css/backend.css')
    .js([
        'resources/assets/js/shared/shared.js',
        'resources/assets/js/frontend/frontend.js'
    ], 'public/js/frontend.js')
    .js([
        'resources/assets/js/shared/shared.js',
        'resources/assets/js/backend/backend.js'
    ], 'public/js/backend.js');

if (mix.inProduction()) {
    mix.version();
}