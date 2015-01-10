<?php namespace Pochika\Markdown;

use Config;
use Illuminate\Support\ServiceProvider;

class MarkdownServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bindShared('markdown', function() {
            return new Parsedown;
        });
    }

}
