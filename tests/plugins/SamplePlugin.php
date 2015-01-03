<?php namespace Pochika\Plugins;

use App\Events\AfterConvert;

class SamplePlugin extends Plugin {

    protected $data;

    public function register()
    {
        $this->listen(AfterConvert::class);
    }
    
    public function handle(AfterConvert $event)
    {
        // $event->entry->content = '';
    }
    
}
