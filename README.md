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

### Installation

1. run `git clone http://github.com/ohida/pochika.git`
1. change into pochika dir `cd pochika`
1. install dependencies `composer install`
1. set permission `chmod -R a+w app/storage`
1. run web server `php artisan serve`

### How to use

#### new post
Run `php artisan pochika:new_post` command

or

Put a markdown file in the posts directory (`source/posts`).  
File name must be yyyy-mm-dd-title.md(markdown) and have yaml-frontmatter like [jekyll](http://jekyllrb.com/docs/frontmatter/).

### License

Pochika is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
