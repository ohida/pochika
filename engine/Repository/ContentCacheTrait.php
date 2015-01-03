<?php namespace Pochika\Repository;

use Event;
use Log;

trait ContentCacheTrait {

    protected $converted_keys = [];

    public function storeConvertedKey($entry)
    {
        if (!$entry->nocache) {
            $this->converted_keys[] = $entry->key;
        }
        return;

        //$class = $this->itemClass();
        //$entry = $params->entry;
        //if ($entry instanceof $class) {
        //    if (!$entry->nocache) {
        //        $this->converted_keys[] = $params->entry->key;
        //    }
        //}
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
        }

        $this->remember($items, true);

        Log::debug('cache updated: '.$cache_id);
    }

}