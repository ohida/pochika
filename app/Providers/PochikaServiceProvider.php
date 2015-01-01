<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Pochika\Pochika;
use Pochika\Config\Config;
use Pochika\Entry\Tag;
use Pochika\Repository\PostRepository;
use Pochika\Repository\PageRepository;
use Pochika\Repository\PluginRepository;
use Pochika\Support\Cache;
use Pochika\Support\Sitemap;

class PochikaServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
		//\App::booted(function(){
			//\Pochika::init();
		//});
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerPochika();
		$this->registerConf();
		$this->registerTheme();
		$this->registerPostRepository();
		$this->registerPageRepository();
		$this->registerPluginRepository();
		$this->registerTag();
		$this->registerSitemap();

		//$this->registerCache();
		//$this->registerTimer();
	}

	public function registerPochika()
	{
		$this->app['pochika'] = $this->app->share(function() {
			return new Pochika;
		});
	}

	public function registerConf()
	{
		$this->app['conf'] = $this->app->share(function() {
			return new Config;
		});
	}

	public function registerTheme()
	{
		$this->app['theme'] = $this->app->share(function() {
			return new Theme;
		});
	}

	public function registerPostRepository()
	{
		$this->app['post_repo'] = $this->app->share(function() {
			return new PostRepository;
		});
	}

	public function registerPageRepository()
	{
		$this->app['page_repo'] = $this->app->share(function() {
			return new PageRepository;
		});
	}

    public function registerPluginRepository()
    {
        $this->app['plugin_repo'] = $this->app->share(function() {
            return new PluginRepository;
        });
    }

	public function registerCache()
	{
		$this->app['cache'] = $this->app->share(function() {
			return new Cache;
		});
	}

	public function registerTag()
	{
		$this->app['tag'] = $this->app->share(function() {
			return new Tag;
		});
	}

	public function registerSitemap()
	{
		$this->app['sitemap'] = $this->app->share(function() {
			return new Sitemap;
		});
	}

	//public function registerTimer()
	//{
	//	$this->app['timer'] = $this->app->share(function() {
	//		return new \Pochika\Support\Timer;
	//	});
	//}

}
