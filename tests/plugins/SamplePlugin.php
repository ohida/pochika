<?php

use App\Events\AfterConvert;

class SamplePlugin extends Plugin {

    public function register()
    {
        $this->listen(AfterConvert::class);
    }
    
    public function handle(AfterConvert $event)
    {
        // $event->entry->content = '';
    }
    
}
