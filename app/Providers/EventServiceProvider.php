<?php namespace App\Providers;

use App\Events\End;
use App\Events\AfterConvert;
use App\Handlers\Events\StoreConvertedKeys;
use App\Handlers\Events\UpdateCache;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		//'event.name' => [
		//	'EventListener',
		//],
		//BeforeConvert::class => [
		//],
		AfterConvert::class => [
			StoreConvertedKeys::class,
		],
		End::class => [
			UpdateCache::class,
		],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

		//
	}

}
