## Pochika

Pochika is a lightweight blog engine.  
It's built on [Laravel4](http://four.laravel.com/).

### Features

* index / archives
* entries
  * markdown files
  * posts / pages
* themes
* plugins
* cache

### Requirements

* php5.4 or more
* [composer](https://github.com/composer/composer)

#### optional

* php extensions
  * msgpack ... reduce cache size
  * sundown ... faster markdown conversion

### Installation

1. run `git clone http://github.com/ohida/pochika.git`
1. change into pochika dir `cd pochika`
1. install dependencies `composer install`
1. set permission `chmod -R a+w app/storage`
1. run `php artisan pochika:init`
1. run web server `php artisan serve`

### How to use

TBD

### License

Pochika is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
