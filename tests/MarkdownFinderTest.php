<?php

use Pochika\Repository\MarkdownFinder;

class MarkdownFinderTest extends TestCase {
    
    use MarkdownFinder;

    function testFind()
    {
        $this->finder();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    function testFindNonExistDir()
    {
        $this->finder('/tmp/non-exist-dir');
    }

    function itemClass()
    {
        return 'Post';
    }

}