<?php namespace Pochika;

use Cache;
use Log;
use PageRepository;
use PluginRepository;
use PostRepository;
use Renderer;
use Tag;
use Theme;

class Pochika {

    private $initialized = false;

    /**
     * Initialize Pochika
     *
     * @param string $config_path
     */
    public function init()
    {
        if ($this->initialized) {
            throw new \LogicException('Already Initialized');
        }

        measure('pochika:init', function() {
            Log::debug('pochika::init (env='.env('APP_ENV').')');
            $this->check();
            $this->load();
            $this->initialized = true;
        });
    }

    public function check()
    {
        $this->checkSource();
        //Theme::check();
    }

    /**
     * Load data
     *
     * @return void
     */
    public function load()
    {
        PluginRepository::load();
        PostRepository::load();
        PageRepository::load();
        
        //Tag::load();
    }

    /**
     * Clear caches
     *
     * @codeCoverageIgnore
     */
    public function clearCache()
    {
        Cache::flush();
        Renderer::clearCache();
    }

    /**
     * Check existence of source dir
     *
     * @throws \NotInitializedException
     */
    public function checkSource()
    {
        if (!file_exists(source_path())) {
            throw new \NotInitializedException;
        }
    }

    /**
     * Finalize
     * called from PochikaMiddleware
     */
    public function end()
    {
        Log::debug('pochika::end');

        $this->unload();
        $this->initialized = false;
    }

    /**
     * Unload data
     *
     * @return void
     */
    public function unload()
    {
        PluginRepository::unload();
        PostRepository::unload();
        PageRepository::unload();
    }

}