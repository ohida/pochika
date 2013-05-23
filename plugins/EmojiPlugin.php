<?php

class EmojiPlugin extends Plugin {

    const PATH = '/emoji';

    public function register()
    {
        $this->listen('entry.after_convert', 'convert');
    }

    public function convert($params)
    {
        $content = &$params->entry->content;

        $names = $this->names();
        $class = element('class', $this->config, 'emoji');

        $content = preg_replace_callback('/:([\w\+\-]+):/', function($matches) use ($names, $class) {
            if (in_array($name = $matches[1], $names)) {
                $url = self::PATH.'/'.rawurlencode($name).'.png';
                return sprintf('<img alt="%s" src="%s" class="%s">', $name, $url, $class);
            }
        }, $content);
    }

    protected function names()
    {
        $cache_id = 'emoji-names';

        $loader = function() {
            $dir = app('path.public').self::PATH;
            foreach (glob($dir.'/*.png') as $file) {
                $names[] = pathinfo($file, PATHINFO_FILENAME);
            }
            return $names;
        };

        if ('production' == App::environment()) {
            return Cache::rememberForever($cache_id, $loader);
        } else {
            return call_user_func($loader);
        }
    }

}
