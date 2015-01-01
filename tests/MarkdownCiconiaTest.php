<?php

require_once 'MarkdownTestCase.php';

class MarkdownCiconiaTest extends MarkdownTestCase {

    public function setUp()
    {
        $this->markdown = new \Pochika\Markdown\Ciconia;
    }

    /**
     *
     */
    public function testAll()
    {
        parent::testAll();
    }

}
