<?php namespace Pochika\Repository;

use Event;
use Log;

trait ContentCacheTrait {

    protected $converted_keys = [];

    public function enableContentCache()
    {
        Event::listen('entry.after_convert', [$this, 'storeKey']);
        Event::listen('site.after_process', [$this, 'updateCache']);
    }

    public function storeKey($params)
    {
        $class = $this->itemClass();
        $entry = $params->entry;
        if ($entry instanceof $class) {
            if (!$entry->nocache) {
                $this->converted_keys[] = $params->entry->key;
            }
        }
    }

    public function updateCache()
    {
        if (!$this->converted_keys) {
            return;
        }

        $cache_id = $this->cacheID();

        Log::debug('converted '.$cache_id.' detected ('.count($this->converted_keys).')');

        $class = $this->itemClass();
        $items = $this->remember();

        foreach ($this->converted_keys as $key) {
            $items[$key] = $class::find($key);
            //$obj = $class::find($key);
            //$item = &$items[$key];
            //$item->converted = true;
            //$item->content = $obj->content;
        }

        $this->remember($items, true);

        Log::debug('cache updated: '.$cache_id);
    }

}