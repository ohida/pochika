<?php namespace Pochika\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Renderer extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'renderer';
    }

}
