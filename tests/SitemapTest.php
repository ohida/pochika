<?php

//use Pochika\Support\Sitemap;

class SitemapTest extends TestCase {

    function testGenerate()
    {
        $xml = Sitemap::generate();
        $this->assertRegExp('/^<\?xml.*?\?>/', $xml);
    }

    /**
     * @expectedException ErrorException
     */
    function testInvalidAppend()
    {
        $post = Post::find(0);
        $post->url = null;
        Sitemap::generate();
    }
    
}
