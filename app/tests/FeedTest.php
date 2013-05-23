<?php

//use Symfony\Component\DomCrawler\Crawler;

class FeedTest extends TestCase {

    protected $feed;

    public function setUp()
    {
        $this->feed = App::make('feed');
    }

    public function testTitle()
    {
        $view = Feed::make();

        $this->assertEquals('pochika-test', $view['title']);
    }

    public function testCdata()
    {
        $method = new ReflectionMethod($this->feed, 'cdata');
        $method->setAccessible(true);

        $this->assertEquals('<![CDATA[aaa]]>', $method->invoke($this->feed, 'aaa'));

        $this->assertEquals('<![CDATA[<br>]]>', $method->invoke($this->feed, '<br>'));
        $this->assertEquals('<![CDATA[<br />]]>', $method->invoke($this->feed, '<br />'));

        $this->assertEquals('<![CDATA[]]]]><![CDATA[>]]>', $method->invoke($this->feed, ']]>'));
    }

    public function testEscape()
    {
        $method = new ReflectionMethod($this->feed, 'escape');
        $method->setAccessible(true);

        $this->assertEquals('&amp;', $method->invoke($this->feed, '&'));
        $this->assertEquals('&quot;', $method->invoke($this->feed, '"'));
        $this->assertEquals('&lt;br&gt;', $method->invoke($this->feed, '<br>'));
    }

    public function testBlogID()
    {
        $view = Feed::make();

        $id = $view['id'];
        $this->assertRegExp('/^tag:.*,[\d]+:.*/', $id);
    }

    public function testPostID()
    {
        $view = Feed::make();

        $entries = $view['entries'];
        $this->assertTrue(isset($entries[0]['id']));
        $this->assertRegExp('/^tag:.*,[\d]+:.*\.post-.*$/', $entries[0]['id']);
    }

    public function testRender()
    {
        $atom = Feed::generate();

        $this->assertStringStartsWith('<?xml ', $atom);
        $this->assertRegExp('/www\.w3\.org\/2005\/Atom/', $atom);
        $this->assertStringEndsWith('</feed>', $atom);
    }

}
