<?php

require_once 'MarkdownTestCase.php';

class MarkdownParsedownTest extends MarkdownTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->markdown = app('markdown');
    }

    public function testClass()
    {
        $class = get_class(app('markdown'));
        $this->assertEquals('Pochika\Markdown\Parsedown', $class);
    }

    public function testAll()
    {
        parent::testAll();
    }
}
