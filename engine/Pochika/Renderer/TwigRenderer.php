<?php namespace Pochika\Renderer;

use App;
use Conf;
use Event;
use Twig_Autoloader;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension_Debug;
use Pochika\Renderer\Twig\Extension as Extension;

class TwigRenderer {

    protected $twig;

    public function __construct()
    {
        $this->twig = $this->initTwig();
    }

    protected function initTwig()
    {
        $debug = 'production' != App::environment();

        Twig_Autoloader::register();

        $dir = app('path.theme');

        $loader = new Twig_Loader_Filesystem([$dir.'/']);

        $twig = new Twig_Environment($loader, [
            'debug' => $debug,
            'auto_reload' => $debug,
            'cache' => self::getCacheDir(true),
        ]);

        $twig->addExtension(new Extension);

        if ($debug) {
            $twig->addExtension(new Twig_Extension_Debug);
        }

        $twig->addGlobal('site', Conf::all());

        return $twig;
    }

    public function getCacheDir($create = false)
    {
        $dir = app('path.root').'/'.Conf::app('twig_cache_dir');

        // @codeCoverageIgnoreStart
        if ($create && !file_exists($dir)) {
            mkdir($dir);
            chmod($dir, 0775);
        }
        // @codeCoverageIgnoreEnd

        return $dir;
    }

    /**
     * render 
     * 
     * @param string $template 
     * @param array $payload 
     * @return string
     */
    public function render($template, $payload = [])
    {
        $event_params = (object) ['template' => &$template, 'payload' => &$payload];

        Event::fire('renderer.before_render', $event_params);

        // render
        $html = $this->twig->render($template, $payload);

        $event_params->html = &$html;
        Event::fire('renderer.after_render', $event_params);

        return $html;
    }

    public function getTwig()
    {
        return $this->twig;
    }

    public function clearCache()
    {
        if (file_exists($this->getCacheDir())) {
            $this->twig->clearTemplateCache();
            $this->twig->clearCacheFiles();
        }
    }

    public function addGlobal($name, $value)
    {
        $this->twig->addGlobal($name, $value);
    }

    public function getGlobals()
    {
        return $this->twig->getGlobals();
    }

}
