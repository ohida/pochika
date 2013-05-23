<?php

class PostRepositoryTest extends TestCase {

    const POST_COUNT = 5;
    const TAG_COUNT = 4;
    const TAG_FIND_COUNT = 2;

    public function testAll()
    {
        $this->assertCount(self::POST_COUNT, PostRepository::all());
    }

    public function testCount()
    {
        $this->assertEquals(self::POST_COUNT, PostRepository::count());
    }

    public function testFind()
    {
        $post = PostRepository::find('2013-03-01-test');
        $this->assertInstanceOf('Post', $post);
        $this->assertEquals('test-0301', $post->title);
    }

    /**
     * @expectedException NotFoundException
     */
    public function testFindNotFound()
    {
        PostRepository::find('not-exist-post');
    }

    public function testFindByIndex()
    {
        $post = PostRepository::find(0);
        $this->assertInstanceOf('Post', $post);
        $this->assertEquals('test-0318', $post->title);

        $post = PostRepository::find(1);
        $this->assertInstanceOf('Post', $post);
        $this->assertEquals('test-0303-hello', $post->title);
    }

    /**
     * @expectedException NotFoundException
     */
    public function testFindByIndexNotFound()
    {
        PostRepository::find(-1);
    }

    public function testFindByTag()
    {
        $posts = PostRepository::findByTag('test-tag');
        $this->assertEquals(self::TAG_FIND_COUNT, count($posts));
    }

    /**
     * @expectedException NotFoundException
     */
    public function testFindByTagNotFound()
    {
        PostRepository::findByTag('not-exist-tag');
    }

    public function testSearch1()
    {
        $q = 'search target';
        $posts = PostRepository::search($q);
        $this->assertCount(2, $posts);
    }

    public function testSearchCase()
    {
        $q = 'I LOVE';
        $posts = PostRepository::search($q);
        $this->assertCount(2, $posts);
    }

    public function testSearchMultiByte()
    {
        $q = 'の';
        $posts = PostRepository::search($q);
        $this->assertCount(1, $posts);

        $q = 'アルクマ';
        $posts = PostRepository::search($q);
        $this->assertCount(1, $posts);
    }

    public function testSearchNotFound()
    {
        $q = 'non exist search query';
        $posts = PostRepository::search($q);
        $this->assertCount(0, $posts);
    }

    public function testFilter()
    {
        $posts = PostRepository::filter(function($post) {
            return preg_match('/i love dogs/', $post->content);
        });

        $this->assertEquals(1, count($posts));

        $posts = PostRepository::filter(function($post) {
            return preg_match('/search target/', $post->content);
        });
        $this->assertEquals(2, $posts->count());
    }

    /**
     * @todo
     */
    public function testKeyNotIncludesExtension()
    {
        $posts = PostRepository::all();

        $posts->each(function($post) {
            $this->assertFalse(!!preg_match('/\.(md|markdown)$/', $post->key));
        });
    }

    /**
     * @expectedException LogicException
     */
    public function testLoadException()
    {
        PostRepository::load();
    }

    public function testKeys()
    {
        $keys = PostRepository::keys();
        $this->assertEquals(self::POST_COUNT, count($keys));

        foreach ($keys as $key) {
            $this->assertRegExp('/^[\d]{4}-[\d]{2}-[\d]{2}-[\w\-]+$/', $key);
        }
    }

    public function testIndex()
    {
        $index = PostRepository::index('2013-03-01-test');
        $this->assertEquals(3, $index);
    }

    /**
     * @expectedException NotFoundException
     */
    public function testIndexNotFound()
    {
        PostRepository::index('xxx');
    }

    /**
     * @expectedException LogicException
     */
    public function testLoadDuplicate()
    {
        PostRepository::load();
    }

    public function testItemClass()
    {
        $repo = App::make('post_repo');

        $method = new ReflectionMethod($repo, 'itemClass');
        $method->setAccessible(true);

        $this->assertEquals('Post', $method->invoke($repo));
    }

}
