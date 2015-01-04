<?php namespace Pochika\Plugins;

use App\Events\Converted;

class EmojiPlugin extends Plugin {

    protected $data;

    public function register()
    {
        $this->listen(Converted::class);
    }
    
    public function handle(Converted $event)
    {
        $content = &$event->entry->content;
        $data = $this->data();
        $css_class = element('class', $this->config, 'emoji');

        $content = preg_replace_callback('/:([\w\+\-]+):/', function($matches) use ($data, $css_class) {
            if (array_key_exists($name = $matches[1], $data)) {
                \Log::debug('emoji convert: :'.$name.':');
                $url = $data[$name];
                return sprintf('<img alt="%s" src="%s" class="%s">', $name, $url, $css_class);
            }
        }, $content);
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
