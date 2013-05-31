<?php

class TwigExtensionTest extends TestCase {

    public function setUp()
    {
        $this->createApplication();
        $this->e = new Pochika\Renderer\Twig\Extension();
    }

    public function testExcerpt()
    {
        $org = 'aaa<!-- more -->zzz';
        $str = $this->e->excerpt($org);
        $this->assertEquals('aaa', $str);

        $org = 'aaa<!-- comment -->zzz';
        $str = $this->e->excerpt($org);
        $this->assertEquals($org, $str);
    }

    public function testHasExcerpt()
    {
        $str = 'aaa<!-- more -->zzz';
        $this->assertTrue($this->e->hasExcerpt($str));

        $str = 'aaa<!-- comment -->zzz';
        $this->assertFalse($this->e->hasExcerpt($str));
    }

    public function testJsTag()
    {
        $res = $this->e->jsTag('a,b,c');
        $count = preg_match_all('/<script\b/', $res);
        $this->assertEquals(3, $count);

        $res = $this->e->jsTag(':jquery');
        $this->assertRegExp('/\bjquery\.min\.js\b/', $res);

        $res = $this->e->jsTag('aaa');
        $this->assertRegExp('|^<script .*/js/aaa\.js\b|', $res);
    }

    public function testCssTag()
    {
        $res = $this->e->cssTag('a,b,c');
        $count = preg_match_all('/<link\b/', $res);
        $this->assertEquals(3, $count);

        $res = $this->e->cssTag('aaa');
        $this->assertRegExp('|^<link .*/css/aaa\.css\b|', $res);
    }

    public function testNamedUrl()
    {
        $this->assertStringEndsWith(URL::to('/').'/archives', $this->e->url(':archives'));
    }

    /**
     * @expectedException ReflectionException
     */
    public function testInvalidNamedUrl()
    {
        $this->e->url(':xxx');
    }

    public function testUrl()
    {
        $expected = URL::to('/').'/about';

        $res = $this->e->url('/about');
        $this->assertEquals($expected, $res);

        $res = $this->e->url('about');
        $this->assertEquals($expected, $res);
    }

    public function testPaginate()
    {
        $posts = Paginator::paginatePosts(Post::all(), 0, 5);
        $res = $this->e->paginate($posts, 'year');
        $keys = array_keys($res);
        $this->assertTrue(2010 < $keys[0] and $keys[0] < 2020);
        $this->assertTrue(isset($res[$keys[0]]['posts']));
    }

    /**
     * @expectedException LogicException
     */
    public function testInvalidPaginate()
    {
        $this->e->paginate([], 'xxx');
    }

    public function testAssetUrl()
    {
        $res = $this->e->asset('favicon.ico');
        $this->assertRegExp('|http://.*/assets/favicon.ico|', $res);
    }

}