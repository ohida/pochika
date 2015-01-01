<?php

class ConfigTest extends TestCase {

    public function testApp()
    {
        $path1 = Conf::app('config_path');
        $path2 = Conf::app('pochika.config_path');
        $this->assertEquals($path1, $path2);
    }

    public function testConfigPath()
    {
        $path = Conf::path();
        $this->assertRegExp('|/tests/|', $path);
        $this->assertStringEndsWith('config.yml', $path);
        $this->assertFileExists($path);
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

    public function testSourcePath()
    {
        $path = source_path();
        $this->assertStringEndsWith('tests/source', $path);
        $this->assertFileExists($path);
    }

    public function testPostsPath()
    {
        $path = source_path('posts');
        $this->assertRegExp('|/tests/|', $path);
        $this->assertStringEndsWith('posts', $path);
        $this->assertFileExists($path);
    }

    public function testThemesExists()
    {
        $path = source_path('themes');
        $this->assertRegExp('|/tests/|', $path);
        $this->assertStringEndsWith('themes', $path);
        $this->assertFileExists($path);
    }

    public function testPluginsExists()
    {
        $path = base_path('plugins');
        $this->assertRegExp('/plugins?/', $path);
        $this->assertFileExists($path);
    }

    public function testRootUrl()
    {
        $this->assertStringEndsWith('localhost', url());
    }

    //public function testFeedUrl()
    //{
    //    $this->assertStringEndsWith('/feed', app('url.feed'));
    //}
    //
    //public function testArchivesUrl()
    //{
    //    $this->assertStringEndsWith('/archives', app('url.archives'));
    //}
    //
    //public function testSearchUrl()
    //{
    //    $this->assertStringEndsWith('/search', app('url.search'));
    //}

    public function testSet()
    {
        Conf::set('markdown', 'sundown');

        $this->assertEquals('sundown', Conf::get('markdown'));
    }

    /**
     * @pectedException ReflectionException
     */
    //public function testInvalidPath()
    //{
    //    app('path.xxx');
    //}

    /**
     * @pectedException ReflectionException
     */
    //public function testInvalidUrl()
    //{
    //    app('url.xxx');
    //}

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

    public function testGetBooleanValue()
    {
        Conf::set('string-true', 'true');
        $this->assertTrue(is_bool(Conf::get('string-true')));
        $this->assertTrue(Conf::get('string-true'));

        Conf::set('string-false', 'false');
        $this->assertTrue(is_bool(Conf::get('string-false')));
        $this->assertFalse(Conf::get('string-false'));

        Conf::set('string-on', 'on');
        $this->assertTrue(is_bool(Conf::get('string-on')));
        $this->assertTrue(Conf::get('string-on'));

        Conf::set('string-off', 'off');
        $this->assertTrue(is_bool(Conf::get('string-off')));
        $this->assertFalse(Conf::get('string-off'));

        Conf::set('bool-true', true);
        $this->assertTrue(is_bool(Conf::get('bool-true')));
        $this->assertTrue(Conf::get('bool-true'));

        Conf::set('bool-false', false);
        $this->assertTrue(is_bool(Conf::get('bool-false')));
        $this->assertFalse(Conf::get('bool-false'));

        Conf::set('string', 'string');
        $this->assertFalse(is_bool(Conf::get('string')));

        Conf::set('num', 1);
        $this->assertFalse(is_bool(Conf::get('num')));
    }

}