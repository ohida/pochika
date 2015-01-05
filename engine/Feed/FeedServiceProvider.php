<?php namespace Pochika\Feed;

use Illuminate\Support\ServiceProvider;

class FeedServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bindShared('feed', function() {
            return new \Pochika\Feed\Atom;
        });
        
        //$this->app['feed'] = $this->app->share(function() {
        //    return new \Pochika\Feed\Atom;
        //});
        //$this->app->bind('Feed', '\Pochika\Feed\Atom')
    }

}
