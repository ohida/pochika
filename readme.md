# Pochika

'Pochika' is a lightweight blog engine built with [Laravel5](http://laravel.com/).

[![Build Status](https://travis-ci.org/ohida/pochika.png?branch=develop)](https://travis-ci.org/ohida/pochika)

## Demo

http://solvalou.net/

## Features

* Index / Archives
* Entries
  * Posts / Pages
  * Markdown files
* Themes
* Plugins
* Cache

## Requirements

* PHP5.6 or more
* [Composer](https://github.com/composer/composer)

## Installation

1. Run `git clone -b develop http://github.com/ohida/pochika.git`
1. Change into pochika dir `cd pochika`
1. Install dependencies `composer install`
1. Set permission `chmod -R a+w storage`
1. Run web server `php -S localhost:3000 -t public`

### Unit Test

run `phpunit --configuration=./phpunit.xml`

## How to use

### Configure

Edit `config.yml`

### Create a new post
Run `php artisan pochika:new` command

or

Put a markdown file in the posts directory (`source/posts`).  
File name must be yyyy-mm-dd-title.md(markdown) and have yaml-frontmatter like [jekyll](http://jekyllrb.com/docs/frontmatter/).

### For better performance

* yaml extension
  * faster yaml reading
  * `pecl install yaml`

## License

Pochika is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

