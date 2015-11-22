<?php

namespace Pochika\Support\Facades;

use Illuminate\Support\Facades\Facade;

class PluginRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'plugin_repo';
    }
}
