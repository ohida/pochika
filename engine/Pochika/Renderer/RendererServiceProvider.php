<?php namespace Pochika\Renderer;

use Illuminate\Support\ServiceProvider;
use Pochika\Renderer\TwigRenderer;

class RendererServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->registerRenderer();
    }

    public function registerRenderer()
    {
        $this->app['renderer'] = $this->app->share(function() {
            return new TwigRenderer();
        });
    }

}
