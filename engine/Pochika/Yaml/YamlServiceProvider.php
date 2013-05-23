<?php namespace Pochika\Yaml;

use Illuminate\Support\ServiceProvider;

class YamlServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->registerYaml();
    }

    public function registerYaml()
    {
        $this->app['yaml'] = $this->app->share(function() {
            return $this->factory();
        });
    }

    protected function factory()
    {
        if (extension_loaded('yaml')) {
            return new PeclYaml;
        } else {
            return new SymfonyYaml;
        }
    }

}
