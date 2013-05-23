<?php

class PochikaTest extends TestCase {

    public function testRoot()
    {
        $this->assertEquals(dirname(dirname(__DIR__)), root());
        $this->assertEquals(dirname(dirname(__DIR__)), app('path.root'));
    }

    public function testLoaded()
    {
        $this->assertGreaterThan(0, PostRepository::count());
        $this->assertGreaterThan(0, PageRepository::count());
        $this->assertGreaterThan(0, PluginRepository::count());
    }

    public function testBindPaths()
    {
        $this->assertStringEndsWith('source', app('path.source'));
        $this->assertStringEndsWith('posts', app('path.posts'));
        $this->assertStringEndsWith('pages', app('path.pages'));
        $this->assertStringEndsWith('plugins', app('path.plugins'));
        $this->assertStringEndsWith('themes', app('path.themes'));
    }

    public function testBindUrls()
    {
        $this->assertStringEndsWith('feed', app('url.feed'));
        $this->assertStringEndsWith('archives', app('url.archives'));
        $this->assertStringEndsWith('search', app('url.search'));
        $this->assertStringEndsWith('assets', app('url.assets'));
    }

}
