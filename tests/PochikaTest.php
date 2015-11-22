<?php

class PochikaTest extends TestCase
{
    public function setUp()
    {
        $this->refreshApplication();
    }

    public function testRoot()
    {
        $this->assertEquals(dirname(__DIR__), base_path());
    }

    public function testLoaded()
    {
        Pochika::init();
        $this->assertGreaterThan(0, PostRepository::count());
        $this->assertGreaterThan(0, PageRepository::count());
        $this->assertGreaterThan(0, PluginRepository::count());
    }

    /**
     * @expectedException NotInitializedException
     */
    public function testNotInitializedException()
    {
        Conf::set('source', 'invalid-source');
        Pochika::init();
    }

    ///**
    // * @expectedException NotFoundException
    // */
    //public function testThemeCheckNotFound()
    //{
    //    Conf::set('theme', 'invalid-theme');
    //    Pochika::checkTheme();
    //}

    /**
     * @expectedException LogicException
     */
    public function testDoubleInit()
    {
        Pochika::init();
        Pochika::init();
    }
}
