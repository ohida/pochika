<?php

class ConfigTest extends TestCase {

    public function setUp()
    {
        $this->createApplication();
    }

    public function testTitle()
    {
        $this->assertEquals('pochika-test', Conf::get('title'));
    }

    public function testReset()
    {
        Conf::reset();
        $this->assertEmpty(Conf::all());
    }

    public function testConfigPath()
    {
        $path = Conf::path();
        $this->assertRegExp('|/tests/|', $path);
        $this->assertStringEndsWith('config.yml', $path);
        $this->assertFileExists($path);
    }

    public function testSourcePath()
    {
        $path = app('path.source');
        $this->assertStringEndsWith('app/tests/source', $path);
        $this->assertFileExists($path);
    }

    public function testPostsPath()
    {
        $path = app('path.posts');
        $this->assertRegExp('|/tests/|', $path);
        $this->assertStringEndsWith('posts', $path);
        $this->assertFileExists($path);
    }

    public function testThemesExists()
    {
        $path = app('path.themes');
        $this->assertRegExp('|/tests/|', $path);
        $this->assertStringEndsWith('themes', $path);
        $this->assertFileExists($path);
    }

    public function testPluginsExists()
    {
        $path = app('path.plugins');
        $this->assertRegExp('/plugins?/', $path);
        $this->assertFileExists($path);
    }

    public function testRootUrl()
    {
        $this->assertStringEndsWith('/', app('url.root'));
    }

    public function testFeedUrl()
    {
        $this->assertStringEndsWith('/feed', app('url.feed'));
    }

    public function testArchivesUrl()
    {
        $this->assertStringEndsWith('/archives', app('url.archives'));
    }

    public function testSearchUrl()
    {
        $this->assertStringEndsWith('/search', app('url.search'));
    }

    public function testSet()
    {
        Conf::set('markdown', 'sundown');

        $this->assertEquals('sundown', Conf::get('markdown'));
    }

    /**
     * @expectedException ReflectionException
     */
    public function testInvalidPath()
    {
        app('path.xxx');
    }

    /**
     * @expectedException ReflectionException
     */
    public function testInvalidUrl()
    {
        app('url.xxx');
    }

    public function testGetWithNoArg()
    {
        $res = Conf::get();

        $this->assertTrue(is_array($res));
        $this->assertTrue(isset($res['source']));
        $this->assertTrue(isset($res['title']));
    }

    public function testSetArray()
    {
        Conf::set([
            'one' => 'aaa',
            'two' => 'bbb',
        ]);

        $this->assertEquals('aaa', Conf::get('one'));
        $this->assertEquals('bbb', Conf::get('two'));
        $this->assertEquals('pochika-test', Conf::get('title'));
    }

}
