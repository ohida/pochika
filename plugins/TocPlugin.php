<?php

class TocPlugin extends Plugin {

    protected $data;

    public function register()
    {
        //$this->listen('entry.after_convert', 'convert');
    }

    //public function handle($params)
    //{
    //    $html = &$params->entry->content;
    //    //dd($content);
    //
    //    $dom = new DOMDocument;
    //    $dom->loadHTML($html);
    //
    //    $xpath = new DOMXPath($dom);
    //    foreach ($xpath->query('//h2') as $item) {
    //    }
    //}

}
