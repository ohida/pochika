<?php

require_once 'app/tests/MarkdownTestCase.php';

class MarkdownExtraTest extends MarkdownTestCase {

    public function setUp()
    {
        $this->markdown = new \Pochika\Markdown\PHPMarkdownExtra;
    }

    public function testAll()
    {
        parent::testAll();
    }

}
