<?php

class PageTest extends TestCase
{
    public function testFind()
    {
        $page = Page::find('about');
        $this->assertTrue(is_object($page));
        $this->assertInstanceOf('Page', $page);
    }

    public function testKey()
    {
        $page = Page::find('about');
        $this->assertEquals('about', $page->key);

        $page = Page::find('etc/resume');
        $this->assertEquals('etc/resume', $page->key);
    }

    /**
     * @expectedException LogicException
     */
    public function testNotAllowedRepositoryMethod()
    {
        Page::load();
    }

    public function testRender()
    {
        $page = Page::find('about');
        $html = $page->render();

        $this->assertRegExp('/^<!doctype/i', $html);
    }

    ///**
    // * @expectedException InvalidArgumentException
    // */
    //public function testInvalidArgumentCount()
    //{
    //    Page::find(0, 0, 0);
    //}

    public function testPlainRender()
    {
        $page = Page::find('plain');
        $html = $page->render();

        $this->assertRegExp('|^<h2>plain</h2>|', $html);
    }

    /**
     * @expectedException NotFoundException
     */
    public function testNotFound()
    {
        Page::find('not-exist');
    }

}
