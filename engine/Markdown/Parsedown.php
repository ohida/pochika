<?php namespace Pochika\Markdown;

class Parsedown extends MarkdownAbstract {

    protected $parser;

    public function __construct()
    {
        $this->parser = new \Parsedown;
    }

    public function run($md)
    {
        return $this->parser->text($md);
    }

}
