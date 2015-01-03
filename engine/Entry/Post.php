<?php namespace Pochika\Entry;

use Layout;
use PostRepository;

class Post extends Entry {

    public $slug;
    public $path_date;
    public $date;
    public $tags = [];

    /**
     * process 
     * 
     * @return void
     */
    protected function process()
    {
        parent::process();

        if (preg_match('/^(.+\/)*(\d{4}-\d{2}-\d{2})-(.*)$/', $this->key, $m)) {
            $this->path_date = strtotime($m[2]);
            $this->slug = $m[3];
        } else {
            throw new \InvalidEntryException;
        }

        $this->url = $this->url();
        $this->title = element('title', $this->data);

        if (!$this->date) {
            $this->date = $this->path_date;
        }

        // tags
        if ($tags = element('tags', $this->data)) {
            $this->tags = is_array($tags) ? $tags : [$tags];
        }
    }

    /**
     * url 
     * 
     * @access protected
     * @return string
     * @todo customize permalink url
     */
    protected function url()
    {
        $date = date('Y/m/d', $this->path_date);
        
        return url($date.'/'.$this->slug);
    }

    /**
     * Render post permalnk
     *
     * @param array $payload
     * @return string
     */
    public function render($payload = [])
    {
        $this->convert();

        $payload = array_merge($payload, [
            'post' => $this,
        ]);

        $layout = element('layout', $this->data, 'post');

        return Layout::find($layout)->render($payload);
    }

    /**
     * Convert this post into a hash for use in Twig templates.
     * 
     * @return array
     */
    public function payload($convert = false)
    {
        if ($convert) {
            $this->convert();
        }

        $data = [];
        foreach ($this->data as $key => $val) {
            $key = preg_replace('/-|\./', '_', $key);
            $data[$key] = $val;
        }

        return array_merge($data, [
            'date' => $this->date,
            'url'  => $this->url,
            'content' => $this->content,
            'published' => $this->published,
//          'tags' => $this->tags,
//          'next' => $this->findNext(),
//          'prev' => $this->findPrev(),
        ]);
    }

    /**
     * Sort tags
     * 
     * @return void
     */
    public function sortTag()
    {
        usort($this->tags, function($a, $b){
            return $a > $b;
        });
    }

    /**
     * Get raw data
     *
     * @return string
     * @todo
     */
    public function raw()
    {
        return file_get_contents($this->path);
    }

    /**
     * Get index of the post in collection
     * 
     * @return int
     */
    public function index()
    {
        return PostRepository::index($this->key);
    }

    /**
     * Find next post
     * 
     * @return object | null
     */
    public function next()
    {
        try {
            return PostRepository::findByIndex($this->index() + 1);
        } catch (\NotFoundException $e) {
            return null;
        }
    }

    /**
     * Find previous post
     * 
     * @return object | null
     */
    public function prev()
    {
        try {
            return PostRepository::findByIndex($this->index() - 1);
        } catch (\NotFoundException $e) {
            return null;
        }
    }

    /**
     * Get converted content
     *
     * @return string
     */
    public function getContent()
    {
        $this->convert();
        return $this->content;
    }

    /**
     * __callStatic 
     * 
     * @param string $name 
     * @param array $argv 
     * @return mixed
     */
    public static function __callStatic($name, $argv)
    {
        if (!in_array($name, ['all', 'clear', 'count', 'find', 'findByTag', 'search'])) {
            throw new \BadMethodCallException('Undefined method: Post::'.$name);
        }
        switch (count($argv)) {
            case 0:
                return PostRepository::$name();
            case 1:
                return PostRepository::$name($argv[0]);
//            case 2:
//                return PostRepository::$name($argv[0], $argv[1]);
//            case 3:
//                return PostRepository::$name($argv[0], $argv[1], $argv[2]);
            default:
                throw new \InvalidArgumentException('Invalid argument count');
        }
    }

    public static function getRepository()
    {
        return app('post_repo');
    }

}
