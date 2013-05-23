<?php namespace Pochika\Config;

use Illuminate\Support\Facades\Config as LaravelConfig;
use Log;
use LogicException;
use Yaml;

class Config {

    protected $path;
    protected $data = [];
    protected $paths = [];
    protected $urls = [];

    public function __construct()
    {
        $root = root();

        $config_path = $this->app('config_path') ?: 'config.yml';
        $this->path = $root.'/'.$config_path;

        if (!is_readable($this->path)) {
            // @codeCoverageIgnoreStart
            throw new \LogicException('cannot read config file: ' . $this->path);
            // @codeCoverageIgnoreEnd
        }

        $this->data = Yaml::parse(file_get_contents($this->path));
    }

    /**
     * get config file path
     * 
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    public function reset()
    {
        unset($this->data);
        $this->data = [];
    }

    public function get($key = null, $default = null)
    {
        if (!$key) {
            return $this->all();
        }

        return element($key, $this->data, $default);
    }

    public function all()
    {
        return $this->data;
    }

    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
    }

    public function app($key, $default = null)
    {
        if (false === strpos($key, '.')) {
            $key = 'pochika.'.$key;
        }

        return LaravelConfig::get($key, $default);
    }

}
