<?php namespace Pochika\Repository;

use App;
use Conf;
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
        $items = [];
        $items += $this->collectUserPlugin();
        $items += $this->collectCorePlugin();

        Log::debug(sprintf('%d plugins loaded', count($items)));

        return $items;
    }

    protected function collectUserPlugin()
    {
        $dir = base_path(Conf::get('plugins', 'plugins'));
        
        return $this->collectPlugin($dir);
    }

    protected function collectCorePlugin()
    {
        $dir = base_path('engine/Plugins');
        $ns = '\\Pochika\\Plugins\\';

        return $this->collectPlugin($dir, $ns);
    }

    protected function collectPlugin($dir, $ns = null)
    {
        $finder = new Finder;
        $finder->files()->name('/.+Plugin\.php$/')->in($dir);

        $items = [];
        foreach ($finder as $file) {
            try {
                $class = $ns.$file->getBasename('.php');
                $obj = new $class;
                $items[$obj->key] = $obj;
            } catch (\InvalidEntryException $e) {
                continue;
            }
        }
        
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
            App::instance($plugin->key, $plugin);
            Log::debug('plugin registered: '.$plugin->key);
        });
    }

}
