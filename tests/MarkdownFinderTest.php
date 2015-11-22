<?php

use Pochika\Repository\MarkdownFinder;

class MarkdownFinderTest extends TestCase
{
    use MarkdownFinder;

    public function testFind()
    {
        $this->finder();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFindNonExistDir()
    {
        $this->finder('/tmp/non-exist-dir');
    }

    public function itemClass()
    {
        return 'Post';
    }
}
