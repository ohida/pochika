<?php

//use Pochika\Support\Sitemap;

class SitemapTest extends TestCase {

    public function testCreate()
    {
        $xml = Sitemap::generate();
        $this->assertRegExp('/^<\?xml.*?\?>/', $xml);
    }

}
