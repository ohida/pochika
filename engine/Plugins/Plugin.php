<?php namespace Pochika\Plugins;

use Conf;
use Event;

abstract class Plugin {

    public $key;

    protected $config = [];

    //const PRIORITY_LOWEST  = -100;
    //const PRIORITY_LOW     = -10;
    //const PRIORITY_NORMAL  = 0;
    //const PRIORITY_HIGH    = 10;
    //const PRIORITY_HIGHEST = 100;

    /**
     * @codeCoverageIgnore
     */
    abstract public function register();

    public function __construct()
    {
        $this->key = $this->makeKey();
        $this->config = $this->loadConfig();

        if (!bool(element('enabled', $this->config, true))) {
            throw new \InvalidEntryException;
        }
    }
    
    protected function makeKey()
    {
        $class = static::class;
        
        if (false !== strpos($class, '\\')) {
            $class = str_replace('Pochika\\Plugins\\', '', $class);
        }
        
        $class = substr($class, 0, strlen($class) - 6);

        return snake_case($class);
    }

    #todo write a test
    public function loadConfig()
    {
        return Conf::get($this->key);
    }

    /**
     * Listen event
     * 
     * @param $event
     * @param null $handler
     */
    protected function listen($event, $handler = null)
    {
        if (!$handler) {
            $handler = static::class;
        }
        Event::listen($event, $handler);
    }

}
