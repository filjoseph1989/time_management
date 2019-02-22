const mix = require('laravel-mix');

require('dotenv').config();

let browsersync_proxy_url = process.env.BROWSERSYNC_PROXY_URL || 'iamlazy.oo';
let browsersync_proxy_port = process.env.BROWSERSYNC_PROXY_PORT || 8080;

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
    .copy('node_modules/admin-lte/dist/img/user1-128x128.jpg', 'public/img')
    .copy('node_modules/admin-lte/dist/img/AdminLTELogo.png', 'public/img')
    .sass('resources/sass/app.scss', 'public/css');

if (mix.inProduction()) {
    mix.version();
} else {
    mix
        .browserSync({
            proxy: browsersync_proxy_url,
            host: browsersync_proxy_url,
            open: 'external',
            port: browsersync_proxy_port
        })
        .sourceMaps();
}
