<?php

//use Pochika\Support\Sitemap;

class SitemapTest extends TestCase
{
    public function testGenerate()
    {
        $xml = Sitemap::generate();
        $this->assertRegExp('/^<\?xml.*?\?>/', $xml);
    }

    /**
     * @expectedException LogicException
     */
    public function testInvalidAppend()
    {
        $post = Post::find(0);
        $post->url = null;
        Sitemap::generate();
    }
}
