<?php namespace App\Handlers\Events;

use App\Events\Converted;
use Conf;
use Pochika\Entry\Entry;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class StoreConvertedKeys {

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
	 * @param  EntryConverted  $event
	 * @return void
	 */
	public function handle(Converted $event)
	{
		if (Conf::get('cache')) {
			$entry = $event->entry;
			$entry->getRepository()->storeConvertedKey($entry);
		}
	}

}
