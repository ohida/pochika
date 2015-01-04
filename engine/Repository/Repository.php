<?php namespace Pochika\Repository;

use Cache;
use Collection;
use Conf;

abstract class Repository {

    protected $collection;

    /**
     * Collect items
     *
     * @return array
     */
    abstract protected function collect();

    /**
     * Load collection
     *
     * @codeCoverageIgnore
     */
    public function load()
    {
        if ($this->count()) {
            throw new \LogicException('Already loaded: '.$this->cacheID());
        }
        
        $items = $this->remember(function() {
            return $this->collect();
        });

        $this->collection = new Collection($items);
    }

    /**
     * Save and load items
     *
     * @param mixed $value
     * @return array
     */
    public function remember($value = null, $force = false)
    {
        if (!Conf::get('cache')) {
            if ($value) {
                return is_callable($value) ? $value() : $value;
            } else {
                throw new \LogicException('Cache is not enabled');
            }
        }
        
        if ($value) {
            return $this->saveCache($value, $force);
        } else {
            return $this->loadCache();
        }
    }

    /**
     * Save items to cache
     *
     * @param mixed $value
     * @return array
     * @todo msgpack
     */
    protected function saveCache($value = null, $force = false)
    {
        if (!Conf::get('cache')) {
            throw new \LogicException('Cache is not enabled');
        }

        if ($force) {
            $this->clearCache();
        }
        
        $cache_id = $this->cacheID();

        if (Cache::has($cache_id)) {
            \Log::debug('cache load: '.$cache_id);
            return Cache::get($cache_id);
        }

        if (is_callable($value)) {
            $value = $value();
        }

        Cache::forever($this->cacheID(), $value);

        return $value;
    }

    /**
     * Load items from cache
     *
     * @return array
     */
    protected function loadCache()
    {
        if (!Conf::get('cache')) {
            throw new \LogicException('Cache is not enabled');
        }

        $cache_id = $this->cacheID();
        if (!Cache::has($cache_id)) {
            throw new \LogicException('Cache not exists: '.$cache_id);
        }

        return Cache::get($cache_id);
    }

    /**
     * Find item(s)
     *
     * @param  string $key
     * @return Post
     */
    public function find($key)
    {
        if (is_string($key)) {
            return $this->findByKey($key);
        } elseif (is_int($key)) {
            return $this->findByIndex($key);
        } elseif (is_callable($key)) {
            return $this->findByFunc($key);
        } else {
            throw new \LogicException('Invalid type of key: '.get_class($key));
        }
    }

    /**
     * Find item by key
     *
     * @param $key
     * @return Object
     */
    public function findByKey($key)
    {
        if ($res = $this->collection->get($key)) {
            return $res;
        }

        throw new \NotFoundException(sprintf('%s not found: %s', $this->itemClass(), $key));
    }

    /**
     * Find item by index
     *
     * @param int $index
     * @return Object
     */
    public function findByIndex($index)
    {
        if (0 <= $index && $index <= $this->count() - 1) {
            return current(array_slice($this->items(), $index, 1));
        }

        throw new \NotFoundException(sprintf('%s not found: %s', $this->itemClass(), $index));
    }

    /**
     * Find item(s) by function
     *
     * @param $func
     * @return Object | Collection
     */
    public function findByFunc(\Closure $func)
    {
        $res = call_user_func($func);

        if ($res->count()) {
            return $res;
        }

        throw new \NotFoundException(sprintf('%s not found: %s', $this->itemClass(), get_class($func)));
    }

    /**
     * Filter collection by callback
     *
     * @param callable $callback
     * @return Collection
     */
    public function filter(\Closure $callback)
    {
        return $this->collection->filter($callback);
    }

    /**
     * Add item
     *
     * @param string $key
     * @param object $obj
     * @return void
     */
//    public function add($key, $obj)
//    {
//        $this->items[$key] = $obj;
//    }

    /**
     * Get count of items
     * 
     * @return int
     */
    public function count()
    {
        if (!$this->collection) {
            return null;
        }

        return $this->collection->count();
    }

    /**
     * Return collection
     *
     * @return array
     */
    public function all()
    {
        return $this->collection;
    }

    /**
     * Clear collection
     *
     * @return array
     */
    public function clear()
    {
        unset($this->collection);
        $this->collection = null;
    }

    /**
     * Pack value
     *
     * @param $value
     * @return string
     */
    protected function pack($value)
    {
        if (extension_loaded('msgpack') && Conf::app('msgpack')) {
            return msgpack_pack($value);
        } else {
            return $value;
        }
    }

    /**
     * Unpack value
     *
     * @param $value
     * @return mixed
     */
    protected function unpack($value)
    {
        if (extension_loaded('msgpack') && Conf::app('msgpack')) {
            return msgpack_unpack($value);
        } else {
            return $value;
        }
    }

    /**
     * Clear cache
     *
     * @param mixed $name
     * @access protected
     * @return void
     */
    public function clearCache()
    {
        \Log::debug('clearcache');
        Cache::forget($this->cacheID());
    }

    /**
     * Get collection source array
     *
     * @return array
     */
    public function items()
    {
        return $this->collection->all();
    }

    /**
     * Get keys of item collection
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->collection->all());
    }

    /**
     * Get index of the item
     *
     * @param $key
     * @return int
     */
    public function index($key)
    {
        if (false !== $index = array_search($key, $this->keys())) {
            return $index;
        }

        throw new \NotFoundException(sprintf('%s not found: index(%d)', $this->itemClass(), $index));
    }

    /**
     * Get class name of repository item
     *
     * @return string
     */
    protected function itemClass()
    {
        if (preg_match('/([\w]+)Repository$/', get_class($this), $matches)) {
            return $matches[1];
        }

        throw new \LogicException('Cannot get class name');
    }

    /**
     * Get cache id
     *
     * @return mixed
     */
    protected function cacheID()
    {
        return str_plural(strtolower($this->itemClass()));
    }

    /**
     * Unload collection
     */
    public function unload()
    {
        unset($this->collection);
        $this->collection = null;
    }

}
