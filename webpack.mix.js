const mix = require('laravel-mix');
require('laravel-mix-bundle-analyzer');
if (!mix.inProduction()) {
    mix.bundleAnalyzer();
}

require('mix-env-file');
mix.env(process.env.ENV_FILE);
mix.config.fileLoaderDirs.fonts = 'assets-vuex/fonts';
mix.config.fileLoaderDirs.images = 'assets-vuex/img';
mix.webpackConfig({
    mode: "development",
    // devtool: "inline-source-map",
    devServer: {
        disableHostCheck: true,
        headers: {
            'Access-Control-Allow-Origin': '*'
        },
        // host: "/",
        // port: 8080
    },
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */



mix
    .ts('resources/ts/index.ts', 'public/assets-vuex/ts')
    .ts('resources/ts/anketaBuilder.ts', 'public/assets-vuex/ts')
    .ts('resources/ts/anketaFrontend.ts', 'public/assets-vuex/ts')
    .ts('resources/ts/stock.ts', 'public/assets-vuex/ts')
    .ts('resources/ts/lead.ts', 'public/assets-vuex/ts')


    .less('resources/ts/assets/less/themes/index.less', 'public/assets-vuex/css')
    // .less('resources/ts/assets/less/anketa.less', 'public/assets-vuex/css')
    .less('resources/ts/assets/less/backend-styles.less', 'public/assets-vuex/css')
    // .postCss('resources/ts/assets/css/anketa.css', 'public/assets-vuex/css')
    // .sass('resources/ts/assets/sass/style.sass', 'public/assets-vuex/css')
    // .copyDirectory('resources/ts/assets/img', 'public/assets-vuex/img')
    .options({
        processCssUrls: false,
    })
;
