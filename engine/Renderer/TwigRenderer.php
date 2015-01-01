<?php namespace Pochika\Renderer;

use App;
use Conf;
use Event;
use Pochika\Renderer\Twig\Extension as Extension;
use Theme;
use Twig_Autoloader;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

class TwigRenderer {

    protected $twig;

    public function __construct()
    {
        $this->twig = $this->initTwig();
        Theme::check();
    }

    protected function initTwig()
    {
        $debug = 'local' == env('APP_ENV') || env('APP_DEBUG');
        //$debug = 'production' != env('APP_ENV');

        \Log::debug('twig debug mode: '.($debug ? 'true' : 'false'));

        Twig_Autoloader::register();

        $loader = new Twig_Loader_Filesystem([theme_path()]);

        $twig = new Twig_Environment($loader, [
            'debug' => $debug,
            'auto_reload' => $debug,
            'cache' => $this->getCacheDir(true),
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
        $dir = base_path(Conf::app('twig_cache_dir'));

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
        $event_params = (object) [
            'template' => &$template,
            'payload' => &$payload
        ];

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
