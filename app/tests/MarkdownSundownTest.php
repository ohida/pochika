<?php

require_once 'app/tests/MarkdownTestCase.php';

class MarkdownSundownTest extends MarkdownTestCase {

    public function setUp()
    {
        if (!extension_loaded('sundown')) {
            $this->markTestSkipped(
                'sundown extension not loaded'
            );
        } else {
            $this->markdown = new \Pochika\Markdown\Sundown;
        }
    }

    /**
     *
     */
    public function testAll()
    {
        parent::testAll();
    }

}
