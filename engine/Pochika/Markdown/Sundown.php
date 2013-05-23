<?php namespace Pochika\Markdown;

class Sundown extends MarkdownAbstract {

    protected $sundown;

    public function __construct()
    {
        $this->sundown = new \Sundown\Markdown(\Sundown\Render\HTML);
    }

    public function translate($md)
    {
        return $this->sundown->render($md);
    }

}
