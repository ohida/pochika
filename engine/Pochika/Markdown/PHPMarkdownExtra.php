<?php namespace Pochika\Markdown;

use Michelf\MarkdownExtra;

class PHPMarkdownExtra extends MarkdownAbstract {

    protected $parser;

    public function __construct()
    {
        $this->parser = new MarkdownExtra();
    }

    public function translate($md)
    {
        return $this->parser->transform($md);
    }

}
