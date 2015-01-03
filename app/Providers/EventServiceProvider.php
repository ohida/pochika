<?php namespace App\Providers;

use App\Events\End;
use App\Events\AfterConvert;
use App\Handlers\Events\StoreConvertedKeys;
use App\Handlers\Events\UpdateCache;

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
		AfterConvert::class => [
			StoreConvertedKeys::class,
		],
		End::class => [
			UpdateCache::class,
		],
	];

}
