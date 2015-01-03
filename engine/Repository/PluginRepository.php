<?php namespace Pochika\Repository;

use App;
use Collection;
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
        $dirs = [
            base_path('engine/Plugins'),
            base_path(Conf::get('plugins', 'plugins')),
        ];

        $finder = new Finder;
        $finder->files()->name('/.+Plugin\.php$/')->in($dirs);

        $ns = '\\Pochika\\Plugins\\';
        $items = [];
        foreach ($finder as $file) {
            try {
                $class = $ns.$file->getBasename('.php');
                $key = str_slug(str_replace([$ns, 'Plugin'], '', $class));
                if (in_array($key, $items)) {
                    throw new \RuntimeException('plugin key duplicated: '.$key);
                }
                if (!class_exists($class)) {
                    include_once $file;
                }
                $items[$key] = new $class;
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
            App::instance($plugin->key, $plugin);
            Log::debug('plugin registered: '.$plugin->key);
        });
    }

}
