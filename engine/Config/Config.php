<?php namespace Pochika\Config;

use Illuminate\Support\Facades\Config as LaravelConfig;
use Yaml;

class Config {

    protected $path;
    protected $data = [];

    public function __construct()
    {
        #todo L5 returns testing/pochika.php's value even if env is 'local'
        if ('testing' == env('APP_ENV')) {
            //todo
            //$config_path = $this->app('config_path') ?: 'tests/config.yml';
            $config_path = 'tests/config.yml';
        } else {
            $config_path = 'config.yml';
        }

        $this->path = base_path($config_path);

        if (!file_exists($this->path())) {
            throw new \NotInitializedException;
        }

        if (!is_readable($this->path)) {
            throw new \LogicException('cannot read config file: '.$this->path);
        }

        $this->data = Yaml::parse(file_get_contents($this->path));
    }

    public function reset()
    {
        unset($this->data);
        $this->data = [];
    }

    public function path()
    {
        return $this->path;
    }

    public function get($key = null, $default = null)
    {
        if (!$key) {
            return $this->all();
        }

        $val = element($key, $this->data, $default);
        if (in_array($val, ['true', 'false', 'on', 'off'])) {
            $val = bool($val);
        }

        return $val;
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

    /**
     * Get pochika config valiable (config/pochika.php)
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function app($key, $default = null)
    {
        if (false === strpos($key, '.')) {
            $key = 'pochika.'.$key;
        }

        return LaravelConfig::get($key, $default);
    }
}