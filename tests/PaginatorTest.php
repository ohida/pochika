<?php

use Pochika\Support\Paginator;

class PaginatorTest extends TestCase
{
    const TOTAL_POSTS = 5;
    const TOTAL_PAGES = 2;

    public function test1()
    {
        $res = Paginator::get(Post::all());
        $this->assertEquals(self::TOTAL_POSTS, $res['total']);
        $this->assertEquals(self::TOTAL_PAGES, $res['pages']);
//        $this->assertEquals(0, $res['posts']->count());
    }

    /**
     * @expectedException InvalidPageException
     */
    public function testOverTotalPage()
    {
        Paginator::get(Post::all(), 10);
    }

    public function testEmpty()
    {
        $res = Paginator::get([]);

        $this->assertNull($res['next_url']);
    }

    /**
     * @expectedException InvalidPageException
     */
    public function testPageIsNotNumeric()
    {
        Paginator::get([], 'a');
    }

    public function testPageIsNumericString()
    {
        $res = Paginator::get([], '1');
        $this->assertEquals(1, $res['page']);
    }

    /**
     * @expectedException InvalidPageException
     */
    public function testPageIsNegativeInt()
    {
        Paginator::get([], -1);
    }

    /*
    public function test1()
    {
        $posts = Post::all();
        // laravel paginator
        $res = Paginator::make($posts, count($posts), 10);
    }
    */
}
