<?php namespace Pochika\Feed;

use Illuminate\Support\ServiceProvider;

class FeedServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->registerFeed();
    }

    public function registerFeed()
    {
        $this->app['feed'] = $this->app->share(function() {
            return new \Pochika\Feed\Atom;
        });
    }

}
