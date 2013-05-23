<?php namespace Pochika\Layout;

use Renderer;

class Layout {

    public $key;
    public $data = [];

    /**
     * __construct 
     * 
     * @param array $data 
     * @return void
     */
    public function __construct($key, $data = [])
    {
        $this->key = $key;
        $this->data = $data;
    }

    /**
     * render 
     * 
     * @param array $payload 
     * @return string
     */
    public function render($payload = [])
    {
        $payload = array_merge(
            $this->data,
            $payload,
            ['layout' => $this->key]
        );

        $tpl = $this->key.'.html';

        return Renderer::render($tpl, $payload);
    }

    /**
     * find 
     * 
     * @param string $key
     * @return Layout
     */
    public static function find($key)
    {
        $path = app('path.theme').'/'.$key.'.html';

        if (file_exists($path)) {
            return new Layout($key);
        }

        throw new \NotFoundException('Layout not found: '.$key);
    }

}
