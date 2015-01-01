<?php

class LayoutTest extends TestCase {

    public function testFind()
    {
        $layout = Layout::find('index');
        $this->assertTrue(is_object($layout));
        $this->assertInstanceOf('Layout', $layout);
    }

    public function testIndex()
    {
        $layout = Layout::find('index');
        $this->assertTrue(is_object($layout));
        $this->assertInstanceOf('Layout', $layout);
        $this->assertEquals('index', $layout->key);
    }

    public function testArchives()
    {
        $layout = Layout::find('archives');
        $this->assertTrue(is_object($layout));
        $this->assertInstanceOf('Layout', $layout);
        $this->assertEquals('archives', $layout->key);
    }

//    public function test404()
//    {
//        $layout = Layout::find('error/404');
//        $this->assertTrue(is_object($layout));
//        $this->assertInstanceOf('Layout', $layout);
//        $this->assertEquals('error/404', $layout->key);
//    }

    /**
     * @expectedException NotFoundException
     */
    public function testFindNotFound()
    {
        Layout::find('invalid-key');
    }

    //public function testError404()
    //{
    //    $html = Layout::find('errors/404')->render();
    //    Layout::find('errors/404');
    //}

}
