<?php

namespace Pochika\Renderer;

use Illuminate\Support\ServiceProvider;

class RendererServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bindShared('renderer', function () {
            return new TwigRenderer;
        });
    }
}
