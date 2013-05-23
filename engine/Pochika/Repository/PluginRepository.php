<?php namespace Pochika\Repository;

use App;
use Collection;
use Conf;
use Finder;
use Log;
use Plugin;
use Profiler;

class PluginRepository extends Repository {

    /**
     * load items
     *
     * @return array
     */
    protected function collect()
    {
        $dir = app('path.plugins');

        if (!file_exists($dir)) {
            return [];
        }

        $finder = new Finder;
        $finder->files()->name('/Plugin\.php$/')->in($dir);

        $items = [];
        foreach ($finder as $file) {
            try {
                $class = $file->getBasename('.php');
                $plugin = new $class;
                $items[$plugin->key] = $plugin;
            } catch (\InvalidEntryException $e) {
                continue;
            }
        }

        Log::debug(sprintf('%d plugins loaded', count($items)));

        return $items;
    }

    /**
     * register plugins
     *
     * @return void
     */
    public function register()
    {
        $this->collection->each(function($plugin) {
            $res = $plugin->register();
            if (false === $res) {
                return;
            }

            App::instance($plugin->key, $plugin);

            Log::debug('plugin registered: '.$plugin->key);
        });
    }

}
