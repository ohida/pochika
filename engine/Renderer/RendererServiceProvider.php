<?php namespace Pochika\Renderer;

use Illuminate\Support\ServiceProvider;

class RendererServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app['renderer'] = $this->app->share(function() {
            return new TwigRenderer();
        });
    }

}
