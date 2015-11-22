<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Pochika\Config\Config;
use Pochika\Entry\Tag;
use Pochika\Pochika;
use Pochika\Repository\PageRepository;
use Pochika\Repository\PluginRepository;
use Pochika\Repository\PostRepository;
use Pochika\Support\Sitemap;

class PochikaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPochika();
        $this->registerConf();
        $this->registerTheme();
        $this->registerPostRepository();
        $this->registerPageRepository();
        $this->registerPluginRepository();
        $this->registerTag();
        $this->registerSitemap();
    }

    public function registerPochika()
    {
        $this->app->bindShared('pochika', function () {
            return new Pochika;
        });
    }

    public function registerConf()
    {
        $this->app->bindShared('conf', function () {
            return new Config;
        });
    }

    public function registerTheme()
    {
        $this->app->bindShared('theme', function () {
            return new Theme;
        });
    }

    public function registerPostRepository()
    {
        $this->app->bindShared('post_repo', function () {
            return new PostRepository;
        });
    }

    public function registerPageRepository()
    {
        $this->app->bindShared('page_repo', function () {
            return new PageRepository;
        });
    }

    public function registerPluginRepository()
    {
        $this->app->bindShared('plugin_repo', function () {
            return new PluginRepository;
        });
    }

    public function registerTag()
    {
        $this->app->bindShared('tag', function () {
            return new Tag;
        });
    }

    public function registerSitemap()
    {
        $this->app->bindShared('sitemap', function () {
            return new Sitemap;
        });
    }
}
