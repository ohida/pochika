<?php namespace Pochika\Markdown;

use Conf;
use Illuminate\Support\ServiceProvider;
use LogicException;
use RuntimeException;

class MarkdownServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->registerMarkdown();
    }

    public function registerMarkdown()
    {
        $this->app['markdown'] = $this->app->share(function() {
            return $this->factory();
        });
    }

    protected function factory()
    {
        $parser = Conf::get('markdown', 'markdown-extra');

        switch ($parser) {
            case 'markdown-extra':
                return new PHPMarkdownExtra;
            // @codeCoverageIgnoreStart
            case 'sundown':
                if (!extension_loaded('sundown')) {
                    throw new RuntimeException('Sundown extension not loaded');
                }
                return new Sundown;
            // @codeCoverageIgnoreEnd
            default:
                throw new LogicException('Invalid markdown parser: ' . $parser);
        }
    }

}
