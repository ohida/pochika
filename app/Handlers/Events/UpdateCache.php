<?php

namespace App\Handlers\Events;

use App\Events\End;
use Conf;
use Pochika\Support\Facades\PageRepository;
use Pochika\Support\Facades\PostRepository;

class UpdateCache
{
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
    public function handle(End $event)
    {
        if (Conf::get('cache')) {
            PostRepository::updateCache();
            PageRepository::updateCache();
        }
    }
}
