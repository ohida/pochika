<?php namespace Pochika\Facades;

use Illuminate\Support\Facades\Facade;

class Yaml extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'yaml';
    }

}
