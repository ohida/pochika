<?php namespace Pochika\Repository;

use App;
use Conf;
use Event;
use Log;
use Post;
use Profiler;

abstract class EntryRepository extends Repository {

    protected $converted_keys = [];

    public function __construct()
    {
        if (bool(Conf::get('cache'))) {
            Event::listen('entry.after_convert', [$this, 'onAfterConvert']);
            App::close([$this, 'onClose']);
        }
    }

    public function onAfterConvert($params)
    {
        $class = $this->itemClass();

        $entry = $params->entry;
        if ($entry instanceof $class) {
            if (!$entry->nocache) {
                $this->converted_keys[] = $params->entry->key;
            }
        }
    }

    public function onClose($req, $res)
    {
        if (bool(Conf::get('cache')) && $this->converted_keys) {
            $cache_id = $this->cacheID();

            Log::debug('converted '.$cache_id.' detected');

            $class = $this->itemClass();

            $items = $this->cache($cache_id);
            foreach ($this->converted_keys as $key) {
                $item = &$items[$key];
                $obj = $class::find($key);
                $item->converted = true;
                $item->content = $obj->content;
            }
            self::clearCache($cache_id);
            self::cache($cache_id, function() use ($items) {
                return $items;
            });

            Log::debug($cache_id.' cache updated');
        }
    }

}
