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
    mix.less('main.less');
    mix.scripts([
        "dependencies/jquery/jquery.min.js",
        "dependencies/angular/angular.min.js",
        "dependencies/angular/angular-route.min.js",
        "dependencies/angular/angular-touch.min.js",
        "dependencies/angular/alertify.js",
        "dependencies/angular/ngAlertify.js",
        "dependencies/bootstrap/bootstrap.min.js",
        "app.js",
        "accueil/controller.js",
        "inscription/controller.js",
        "inscription/etudiant/controller.js",
        "inscription/gestionnaire/controller.js",
        "livre/ajout/controller.js",
        "livre/reservation/controller.js",
        "livre/recuperation/controller.js",
        "livre/remise/controller.js",
        "login/controller.js",
        "login/gestionnaire/controller.js",
        "login/etudiant/controller.js",
        "coop/transfert/controller.js",
        "coop/import/controller.js"
    ]);
});
