<?php namespace Pochika;

use App;
use Cache;
use Conf;
use Log;
use PostRepository;
use PageRepository;
use PluginRepository;
use Profiler;
use Renderer;
use URL;

class Pochika {

    /**
     * Initialize Pochika
     *
     * @param string $config_path
     */
    public function init()
    {
        Profiler::startTimer(__METHOD__);
        Log::debug('pochika::init (env='.env().')');

        $this->bindPaths();
        $this->bindUrls();

        $this->load();

        Profiler::endTimer(__METHOD__);
    }

    /**
     * Bind paths
     *
     * @return void
     */
    public function bindPaths()
    {
        $root   = root();
        $source = $root.'/'.Conf::get('source', 'source');

        $paths = [
            'root'    => $root,
            'source'  => $source,
            'posts'   => $source.'/posts',
            'pages'   => $source.'/pages',
            'themes'  => $source.'/themes',
            'theme'   => $source.'/themes/'.Conf::get('theme'),
            'plugins' => $root.'/'.Conf::get('plugins', 'plugins'),
        ];

        foreach ($paths as $key => $value) {
            App::instance("path.{$key}", $value);
        }
    }

    /**
     * Bind URLs
     *
     * @return void
     */
    public function bindUrls()
    {
        $root = \Str::finish(URL::to('/'), '/');

        $urls = [
            'root'     => $root,
            'feed'     => $root.'feed',
            'archives' => $root.'archives',
            'search'   => $root.'search',
            'assets'   => $root.'assets',
        ];

        foreach ($urls as $key => $value) {
            App::instance("url.{$key}", $value);
        }
    }

    /**
     * Load essential data
     * 
     * @return void
     */
    public function load()
    {
        Profiler::startTimer(__METHOD__);
        Log::debug('pochika::load begin');

        PluginRepository::load();
        PluginRepository::register();

        PostRepository::load();
        PageRepository::load();

        Log::debug('pochika::load end');
        Profiler::endTimer(__METHOD__);
    }

    /**
     * @codeCoverageIgnore
     */
    public function clearCache()
    {
        Cache::flush();
        Renderer::clearCache();
    }

}
