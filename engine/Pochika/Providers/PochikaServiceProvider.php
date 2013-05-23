<?php namespace Pochika\Providers;

use Illuminate\Support\ServiceProvider;
use Pochika\Pochika;
use Pochika\Config\Config;
use Pochika\Repository\PageRepository;
use Pochika\Repository\PostRepository;
use Pochika\Repository\PluginRepository;
use Pochika\Support\Paginator;

class PochikaServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->registerPochika();
        $this->registerConf();
        $this->registerPostRepository();
        $this->registerPageRepository();
        $this->registerPluginRepository();
    }

    public function registerPochika()
    {   
        $this->app['pochika'] = $this->app->share(function() {
            return new Pochika();
        });
    }

    public function registerConf()
    {
        $this->app['conf'] = $this->app->share(function() {
            return new Config();
        });
    }

    public function registerPostRepository()
    {
        $this->app['post_repo'] = $this->app->share(function() {
            return new PostRepository();
        });
    }

    public function registerPageRepository()
    {
        $this->app['page_repo'] = $this->app->share(function() {
            return new PageRepository();
        });
    }

    public function registerPluginRepository()
    {
        $this->app['plugin_repo'] = $this->app->share(function() {
            return new PluginRepository();
        });
    }

}
