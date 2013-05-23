<?php namespace Pochika\Facades;

use Illuminate\Support\Facades\Facade;

class PostRepository extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'post_repo';
    }

}
