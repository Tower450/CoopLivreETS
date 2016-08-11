## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Comment mettre en place le serveur local

Il faut créer un virtual host et ensuite créer un lien symbolic entre le dossier du projet dans le dossier htdoc. Pour ce faire voici quoi écrire :

mklink /J C:\path\dossier\liensymbolic C:\path\dossier\reel

Pour créer un virtual host, simplement communiquer avec moi ;)

## Configurer le projet laravel

Installer toutes les dépendances avec la commande,

composer install;

http://laravel.com/docs/5.0/elixir

http://laravel.com/docs/5.0/configuration

## Commandes laravel utiles

Pour creer des migrations(les tables sql)

php artisan make:migration create_cooperative_table --create=cooperatives
php artisan make:migration create_etudiant_table --create=etudiants
php artisan make:migration create_gestionnaire_table --create=gestionnaires
php artisan make:migration create_reservation_table --create=gestionnaires

php artisan migrate
php artisan migrate:reset

composer dump-autoload //permet de clearer la cache de composer

##Mysql notes
ALTER TABLE  description_livres  
ADD FULLTEXT(title, author);

CREATE FULLTEXT INDEX title_idx
ON description_livres(title);

CREATE FULLTEXT INDEX author_idx
ON description_livres(author);

##Server notes
sudo a2enmod rewrite //for pretty url
sudo chmod -R 775 app/storage

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
