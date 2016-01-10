var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    // mix.sass('app.scss');

    var bowerDir = './resources/assets/vendor/';

    //var lessPaths = [
    //    bowerDir + 'bootstrap/less',
    //    bowerDir + 'font-awesome/less'
    //];

    // pre compile coffee script and generate resources/assets/js/coffee.js
    //var coffeeJsFileName = 'coffee.js';
    //var coffeeJsFilePath = 'resources/assets/js/' + coffeeJsFileName;
    //mix.coffee([
    // 'confirm.coffee'
    //], coffeeJsFilePath);

    // less
    //mix.less('app.less', 'public/css');

    // vendor.css
    //mix.styles([
    //    'bootstrap/dist/css/bootstrap.css',
    //    'font-awesome/css/font-awesome.css',
    //    'datetimepicker/jquery.datetimepicker.css'
    //], 'public/css/vendor.css', bowerDir);

    // vendor.js
    mix.scripts([
        'jquery/dist/jquery.min.js'
    ], 'public/js/vendor.js', bowerDir);

    // app.js
    mix.scripts([
        'app.js',
        'const.js'
    ], 'public/js/app.js');

    // app.css
    //mix.styles([
    //    'app.css'
    //], 'public/css/app.css');

    // version
    mix.version([
        'public/js/app.js',
        'public/js/vendor.js'
    ]);


});