<?php namespace Pochika\Markdown;

use Config;
use Illuminate\Support\ServiceProvider;

class MarkdownServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app['markdown'] = $this->app->share(function() {
            return new Parsedown;
        });
    }

}
