<?php

namespace Pochika\Plugins;

use App\Events\AfterConvert;

class EmojiPlugin extends Plugin
{
    protected $data;

    public function register()
    {
        $this->listen(AfterConvert::class);
    }

    public function handle(AfterConvert $event)
    {
        $this->convert($event->entry->content);

        if (isset($event->entry->title)) {
            $this->convert($event->entry->title);
        }
    }

    protected function convert(&$text)
    {
        $data = $this->data();
        $css_class = element('class', $this->config, 'emoji');

        $escaped = false;
        if (false !== strpos($text, '\\:')) {
            $text = preg_replace("/\\\:([a-z0-9-_]+):/", '<!--emoji[$1]-->', $text);
            $escaped = true;
        }

        $text = preg_replace_callback('/:([\w\+\-]+):/', function ($matches) use ($data, $css_class) {
            if (array_key_exists($name = $matches[1], $data)) {
                return sprintf('<img alt="%s" src="%s" class="%s">', $name, $data[$name], $css_class);
            }
        }, $text);

        if ($escaped) {
            $text = preg_replace('/<!--emoji\[([a-z0-9-_]+)\]-->/', ':$1:', $text);
        }
    }

    protected function data()
    {
        if ($this->data) {
            return $this->data;
        }

        $path = storage_path('emoji/emojis.json');
        $buff = file_get_contents($path);
        $this->data = json_decode($buff, true);

        return $this->data;
    }
}
