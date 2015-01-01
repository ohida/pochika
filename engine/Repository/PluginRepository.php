<?php namespace Pochika\Repository;

use App;
use Collection;
use Finder;
use Log;
use Plugin;

class PluginRepository extends Repository {

    /**
     * Load items
     */
    public function load()
    {
        parent::load();
        $this->register();
    }

    /**
     * Collect items
     *
     * @return array
     */
    protected function collect()
    {
        $dir = base_path('plugins');

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
     * Register plugins
     *
     * @return void
     */
    public function register()
    {
        $this->collection->each(function($plugin) {
            $plugin->register();
            //if (false === $res) {
            //    return;
            //}

            App::instance($plugin->key, $plugin);
            Log::debug('plugin registered: '.$plugin->key);
        });
    }

}
