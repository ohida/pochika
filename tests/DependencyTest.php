<?php

class DependencyTest extends TestCase {

    public function setUp()
    {
    }

    public function testEnv()
    {
        $this->assertTrue(is_bool(env('APP_DEBUG')));
    }

    public function testStrSlug()
    {
        $this->assertEquals('a-b-c', str_slug('a b c'));
        $this->assertEquals('eo', str_slug('あいうeo'));
    }

    public function testArraySort()
    {
        $items = ['a'=>1, 'b'=>2, 'c'=>3];
        $items = array_sort($items, function($item) {
            return -$item;
        });
        $this->assertEquals(['c'=>3,'b'=>2,'a'=>1], $items);
    }

    //public function testURLs()
    //{
    //    $this->assertEquals('http://localhost', URL::to('/'));
    //    $this->assertEquals('http://localhost/archives', URL::to('archives'));
    //}

}