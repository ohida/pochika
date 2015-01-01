<?php

class PageRepositoryTest extends TestCase {

    const PAGE_COUNT = 4;

    public function testAll()
    {
        Log::debug("testAll");
        $pages = PageRepository::all();
        Log::debug("end testAll");
        $this->assertCount(self::PAGE_COUNT, $pages->all());
    }

    public function testCount()
    {
        $this->assertEquals(self::PAGE_COUNT, PageRepository::count());
    }

    public function testFind()
    {
        $page = PageRepository::find('about');
        $this->assertInstanceOf('Page', $page);
    }

    /*
    public function testFindByFullName()
    {
        $page = PageRepository::find('about.markdown');
        $this->assertInstanceOf('Page', $page);
    }
    */

    /**
     * @expectedException NotFoundException
     */
    public function testFindNotFound()
    {
        PageRepository::find('not-exist-page');
    }

    /**
     * @expectedException LogicException
     */
    public function testLoadDuplicate()
    {
        PageRepository::load();
    }

    public function testFindByIndex()
    {
        $page = PageRepository::find(0);

        $this->assertTrue($page instanceof Page);
    }

    /**
     * @expectedException NotFoundException
     */
    public function testFindByIndexNotFound()
    {
        PageRepository::find(99);
    }

    public function testUnload()
    {
        PostRepository::unload();
        $this->assertNull(PostRepository::count());

        PageRepository::unload();
        $this->assertNull(PageRepository::count());

        PluginRepository::unload();
        $this->assertNull(PluginRepository::count());
    }
}
