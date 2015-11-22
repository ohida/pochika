<?php

class SiteTest extends TestCase
{
    public function setUp()
    {
        //$this->app = $this->createApplication();
        $this->refreshApplication();
    }

    public function testIndex()
    {
        $res = $this->call('GET', '/');
        $this->assertResponseOk();
    }

    public function testArchives()
    {
        $res = $this->call('GET', '/archives');
        $this->assertResponseOk();
    }

    public function testPost()
    {
        $res = $this->call('GET', '/2013/01/01/test');
        $this->assertResponseOk();
        $this->assertRegExp('|<h1 id="title">pochika-test</h1>|', $res->getContent());
    }

    public function testNotFound()
    {
        $res = $this->call('GET', '/2013/01/01/invalid-post');
        $this->assertResponseStatus(404);
    }

    public function testFormatContent()
    {
        $res = $this->call('GET', '/2013/01/01/test?format=content');
        $content = $res->getContent();
        $this->assertStringStartsWith('<p>', $content);
        $this->assertStringEndsWith('</p>', $content);
    }

    public function testInvalidFormat()
    {
        putenv('APP_DEBUG=false');
        $res = $this->call('GET', '/2013/01/01/test?format=invalid');
        putenv('APP_DEBUG=true');
        $this->assertResponseStatus(500);
    }

    public function testFeed()
    {
        $res = $this->call('GET', '/feed');
        $content = $res->getContent();
        $this->assertResponseOk();
        $this->assertStringStartsWith('<?xml', $content);
        $this->assertStringEndsWith('</feed>', $content);
        $this->assertEquals('application/xml', $res->headers->get('content-type'));
        $this->assertEquals('utf-8', $res->headers->get('charset'));
    }

    public function testSearch()
    {
        $res = $this->call('GET', '/search');
        $this->assertResponseOk();

        $res = $this->call('GET', '/search?q=hello');
        $this->assertResponseOk();

        $q = str_repeat('a', Config::get('pochika.search_query_max') + 1);
        $res = $this->call('GET', '/search?q='.$q);
        $this->assertSessionHasErrors();
        $this->assertResponseStatus(302);
    }

    public function testAbout()
    {
        $res = $this->call('GET', '/about');
        $content = $res->getContent();
        $this->assertResponseOk();
        $this->assertRegExp('|<h1 class="entry-title">About</h1>|', $content);
    }

    public function testFeedLink()
    {
        $res = $this->call('GET', '/');
        $this->assertRegExp('|<link href=".*?/feed"|', $res->getContent());
    }
}
