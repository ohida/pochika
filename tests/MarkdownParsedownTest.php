<?php

use Pochika\Markdown\Parsedown;

require_once 'MarkdownTestCase.php';

class MarkdownParsedownTest extends MarkdownTestCase {

    public function setUp()
    {
        parent::setUp();
        $this->markdown = new Parsedown;
    }

    public function testAll()
    {
        parent::testAll();
    }

}
