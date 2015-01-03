<?php namespace App\Handlers\Events;

use Conf;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Pochika\Support\Facades\PostRepository;
use Pochika\Support\Facades\PageRepository;

class UpdateCache {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  PochikaEnded  $event
	 * @return void
	 */
	public function handle(\App\Events\End $event)
	{
		\Log::debug(__METHOD__);
		if (!Conf::get('cache')) {
			return;
		}
		PostRepository::updateCache();
		PageRepository::updateCache();
	}

}
