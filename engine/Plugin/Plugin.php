<?php namespace Pochika\Plugin;

use Conf;
use Event;

abstract class Plugin {

    public $key;
    public $name;

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
        $class = get_class($this);

        $this->name = snake_case($class);
        $this->key = str_replace('_plugin', '', $this->name);

        $this->config = $this->loadConfig();

        if (!bool(element('enabled', $this->config, true))) {
            throw new \InvalidEntryException;
        }
    }

    public function loadConfig()
    {
        return Conf::get($this->name);
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
