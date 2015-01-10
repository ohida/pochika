<?php namespace Pochika\Feed;

use Illuminate\Support\ServiceProvider;

class FeedServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bindShared('feed', function() {
            return new \Pochika\Feed\Atom;
        });
    }

}
