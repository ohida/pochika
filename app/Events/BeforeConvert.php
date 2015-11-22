<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Pochika\Entry\Entry;

class BeforeConvert extends Event
{
    use SerializesModels;

    public $entry;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }
}
