<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class End extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
