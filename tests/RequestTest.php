<?php

class RequestTest extends TestCase
{
    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertTrue($this->client->getResponse()->isOk());

        $this->assertCount(1, $crawler->filter('h1'));
        $this->assertCount(3, $crawler->filter('h2'));

        $title = $crawler->filter('title')->text();
        $this->assertEquals('pochika-test', $title);
    }

    public function testIndexPage2()
    {
        $crawler = $this->client->request('GET', '/page/2');

        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('title:contains("pochika")'));
    }

    public function testIndexPost()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals('test-0318', $crawler->filter('h2')->first()->text());
    }

    public function testPost()
    {
        $crawler = $this->client->request('GET', '/2013/03/01/test');

        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h2'));
    }

    public function testTag()
    {
        $crawler = $this->client->request('GET', '/tag/test');

        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertCount(3, $crawler->filter('h2'));
    }

    public function testFeed()
    {
        $crawler = $this->client->request('GET', '/feed');

        $this->assertCount(5, $crawler->filter('entry'));

        $this->assertEquals('Pochika', $crawler->filter('generator')->eq(0)->text());
    }

    /**
     * @expectedException NotFoundException
     * // Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testMissing()
    {
        $this->client->request('GET', '/null');
    }

    /**
     * @expectedException NotFoundException
     */
    public function testNotFound()
    {
        $this->client->request('GET', '/tag/nonexisttag');
    }

    public function testSearch()
    {
        $crawler = $this->client->request('GET', '/search?q=love');
        $this->assertCount(2, $crawler->filter('h2'));
        $this->assertEquals('search result', $crawler->filter('h3')->first()->text());
    }

    public function testSearchNotFound()
    {
        $crawler = $this->client->request('GET', '/search?q=xxxyyyzzz');
        $this->assertCount(0, $crawler->filter('h2'));
        $this->assertEquals('search result', $crawler->filter('h3')->first()->text());
    }

    public function testPermalink()
    {
        $crawler = $this->client->request('GET', '/2013/03/03/test');
        $this->assertEquals('pochika-test', $crawler->filter('h1#title')->text());
        $this->assertEquals('test', $crawler->filter('h2')->text());
        $this->assertTrue(!!strpos('this is a test', $crawler->filter('h2')->text()));
    }

    public function testRaw()
    {
        $this->client->request('GET', '/2013/03/03/test?format=raw');
        $str = $this->client->getResponse()->getContent();

        $this->assertStringStartsWith('---', $str);
    }
}
