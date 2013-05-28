## Pochika

'Pochika' is a lightweight blog engine.  
It's built on [Laravel4](http://four.laravel.com/).

### Demo

http://solvalou.net/

### Features

* Index / Archives
* Entries
  * Markdown files
  * Posts / Pages
* Themes
* Plugins
* Cache

### Requirements

* PHP5.4 or more
* [Composer](https://github.com/composer/composer)

### Installation

1. Run `git clone http://github.com/ohida/pochika.git`
1. Change into pochika dir `cd pochika`
1. Install dependencies `composer install`
1. Set permission `chmod -R a+w app/storage`
1. Run web server `php artisan serve`

### Unit Test

run `phounit`

### How to use

#### Configure

Edit `config.yml`

#### Create a new post
Run `php artisan pochika:new_post` command

or

Put a markdown file in the posts directory (`source/posts`).  
File name must be yyyy-mm-dd-title.md(markdown) and have yaml-frontmatter like [jekyll](http://jekyllrb.com/docs/frontmatter/).

### For better performance

* yaml extension
  * faster yaml reading
  * `pecl install yaml`

* sundown extension
  * faster markdown translation
  * `pecl install sundown-beta`

* msgpack extension
  * reduce cache size
  * `pecl install msgpack-beta`

### License

Pochika is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Build Status

[![Build Status](https://travis-ci.org/ohida/pochika.png?branch=master)](https://travis-ci.org/ohida/pochika)
