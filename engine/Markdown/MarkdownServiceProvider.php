<?php namespace Pochika\Markdown;

use Config;
use Illuminate\Support\ServiceProvider;

class MarkdownServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app['markdown'] = $this->app->share(function() {
            return new Parsedown;
            //return $this->factory();
        });
    }

    protected function factory()
    {
        $parser = Config::get('pochika.markdown_parser', 'parsedown');

        switch ($parser) {
            case 'parsedown':
                return new Parsedown;
            // @codeCoverageIgnoreStart
            case 'markdown-extra':
                return new PHPMarkdownExtra;
            case 'sundown':
                if (!extension_loaded('sundown')) {
                    throw new \RuntimeException('Sundown extension not loaded');
                }
                return new Sundown;
            // @codeCoverageIgnoreEnd
            default:
                throw new \LogicException('Invalid markdown parser: '.$parser);
        }
    }

}
