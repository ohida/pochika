<?php namespace App\Events;

use App\Events\Event;
use Pochika\Entry\Entry;

use Illuminate\Queue\SerializesModels;

class BeforeConvert extends Event {

	use SerializesModels;
	
	protected $entry;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(Entry &$entry)
	{
		$this->entry = &$entry;
	}

}
