<?php namespace Pochika\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Feed extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'feed';
    }

}
