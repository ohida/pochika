<?php

class PostTest extends TestCase {

    const POST_COUNT = 5;
    const TAG_COUNT = 4;
    const TAG_FIND_COUNT = 2;

    public function testAll()
    {
        $this->assertCount(self::POST_COUNT, Post::all());
    }

    public function testCount()
    {
        $this->assertEquals(self::POST_COUNT, Post::count());
    }

    public function testFind()
    {
        $post = Post::find('2013-03-01-test');
        $this->assertTrue(is_object($post));
        $this->assertInstanceOf('Post', $post);
        $this->assertEquals('test-0301', $post->title);
    }

    /*
    public function testFindByFullName()
    {
        $post = Post::find('2013-03-01-test.markdown');
        $this->assertTrue(is_object($post));
        $this->assertInstanceOf('Post', $post);
        $this->assertEquals('test-0301', $post->title);
    }
    */

    public function testFindByIndex()
    {
        $post = Post::find(0);
        $this->assertTrue(is_object($post));
        $this->assertInstanceOf('Post', $post);
        $this->assertEquals('test-0318', $post->title);
    }
            
    public function testFindByIndex2()
    {
        $post = Post::find(1);
        $this->assertTrue(is_object($post));
        $this->assertInstanceOf('Post', $post);
        $this->assertEquals('test-0303-hello', $post->title);
    }

    public function testFindByIndexLast()
    {
        $post = Post::find(self::POST_COUNT-1);
        $this->assertTrue(is_object($post));
        $this->assertInstanceOf('Post', $post);
        $this->assertEquals(self::POST_COUNT-1, $post->index());
    }

    /**
     * @expectedException Pochika\Exception\NotFoundException
     */
    public function testFindByIndexNotFound()
    {
        Post::find(-1);
    }

    /**
     * @expectedException Pochika\Exception\NotFoundException
     */
    public function testFindByIndexNotFound2()
    {
        Post::find(255);
    }

    /**
     * @expectedException Pochika\Exception\NotFoundException
     */
    public function testFindNotFound()
    {
        Post::find('not-exist-post');
    }

    public function testFindByTag()
    {
        $posts = Post::findByTag('test-tag');
        $this->assertEquals(self::TAG_FIND_COUNT, count($posts));
    }

    /**
     * @expectedException Pochika\Exception\NotFoundException
     */
    public function testFindByTagNotFound()
    {
        Post::findByTag('not-exist-tag');
    }

    public function testClear()
    {
        Post::clear();
        $this->assertFalse(!!Post::count());
    }

    public function testSortTag()
    {
        $post = Post::find(0);
        $post->tags = ['b', 'c', 'a'];
        $post->sortTag();
        $this->assertEquals(['a', 'b', 'c'], $post->tags);
    }

    public function testUrl()
    {
        $post = Post::find('2013-03-01-test');
        $this->assertStringStartsWith('http', $post->url);
        $this->assertStringEndsWith('/2013/03/01/test', $post->url);
    }

    public function testKey()
    {
        $post = Post::find('2013-03-01-test');
        $this->assertEquals('2013-03-01-test', $post->key);
    }

    /**
     * @expectedException LogicException
     */
    public function testCallStaticWithTooMuchArgs()
    {
        Post::method(0, 1, 2, 3, 4);
    }

    public function testPayload()
    {
        $post = Post::find(0);
        $payload = $post->payload();
        $this->assertFalse($post->converted);

        $post = Post::find(0);
        $payload = $post->payload(true);
        $this->assertTrue($post->converted);

        $this->assertTrue(is_array($payload));
        $this->assertTrue(isset($payload['title']));
        $this->assertTrue(isset($payload['content']));
    }

    /**
     * @expectedException LogicException
     */
    public function testNotAllowedRepositoryMethod()
    {
        Post::load();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentCount()
    {
        Post::find(0, 0, 0);
    }

    /**
     * @expectedException InvalidEntryException
     */
    public function testInvalidFilenameException()
    {
        $file = source_path('posts').'/'.'2013-0301-invalid.md';
        new Post($file);
    }

    public function testGetNonExistValue()
    {
        $post = Post::find(0);
        $this->assertEquals(null, $post->xxx);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testNonExistFile()
    {
        new Post(source_path('posts').'/xxx');
    }

    /**
     * @expectedException InvalidEntryException
     */
    public function testDraft()
    {
        new Post(source_path('posts').'/2013-03-01-draft.md');
    }

    public function testIndex()
    {
        $this->assertEquals(0, Post::find(0)->index());
        $this->assertEquals(1, Post::find(1)->index());
        $this->assertEquals(2, Post::find(2)->index());
    }

    public function testFindNext()
    {
        $post = Post::find(0)->next();
        $this->assertEquals(1, $post->index());

        $post = Post::find(2)->next();
        $this->assertEquals(3, $post->index());

        $post = Post::find(self::POST_COUNT-1)->next();
        $this->assertNull($post);
    }

    public function testFindPrev()
    {
        $post = Post::find(1)->prev();
        $this->assertEquals(0, $post->index());

        $post = Post::find(3)->prev();
        $this->assertEquals(2, $post->index());

        $post = Post::find(0)->prev();
        $this->assertNull($post);
    }

    /*
    public function testCallStatic()
    {
        $repo = $this->getMock('PostRepository', ['count']);
        $repo->expects($this->atLeastOnce())
            ->method('count')
            ->will($this->returnValue(0)) ;

        Post::count();
    }
    */

}
