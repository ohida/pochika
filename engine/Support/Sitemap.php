<?php namespace Pochika\Support;

use Pochika\Entry\Page;
use Pochika\Entry\Post;

class Sitemap {
    
    protected $items = [];

    public function __construct()
    {
    }

    public function generate()
    {
        $this->append([
            ['url' => url()],
            ['url' => url('archives')],
            ['url' => url('search')],
            ['url' => url('feed')],
        ]);

        $this->append(Post::all());
        $this->append(Page::all());
        
        return $this->render();
    }

    protected function append($item)
    {
        if (is_object($item) && 'Illuminate\Support\Collection' == get_class($item)) {
            $item->each(function($row){
                $this->append($row);
            });
            return;
        }

        if (is_array($item) && !is_assoc($item)) {
            foreach ($item as $row) {
                $this->append($row);
            }
            return;
        }

        if (!isset($item['url'])) {
            throw new \LogicException('Sitemap item must have `url` field');
        }

        $arr = [
            'loc' => $item['url'],
        ];
        if (isset($item['date'])) {
            $arr['lastmod'] = date('c', $item['date']);
        }
        
        $this->items[] = $arr;
    }

    public function render()
    {
        return view('sitemap')->with('items', $this->items)->render();
    }

}