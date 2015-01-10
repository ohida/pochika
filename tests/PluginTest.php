<?php

use Mockery as m;

class PluginTest extends TestCase {

    public function testEmojiPlugin()
    {
        $event = m::mock('App\Events\AfterConvert');
        $post = m::mock('Pochika\Entry\Post');

        $event->entry = $post;
        
        $plugin = PluginRepository::find('emoji');
        
        $post->content = ':octocat:';
        $plugin->handle($event);
        $this->assertRegExp('/<img .*?>/', $post->content);
        
        $post->content = ':non-potable_water:';
        $plugin->handle($event);
        $this->assertRegExp('/<img .*?>/', $post->content);
    }
    
    public function testEmojiEscape()
    {
        $event = m::mock('App\Events\AfterConvert');
        $post = m::mock('Pochika\Entry\Post');

        $event->entry = $post;
        $post->content = '\\:non-potable_water:';

        $plugin = PluginRepository::find('emoji');
        $plugin->handle($event);

        $this->assertRegExp('/:non-potable_water:/', $post->content);
    }

    public function testTocPlugin()
    {
        $event = m::mock('App\Events\AfterConvert');
        $post = m::mock('Pochika\Entry\Post');
        
        $event->entry = $post;
        
        $plugin = PluginRepository::find('toc');

        $post->content = <<<EOF
{:TOC}

<h2>one</h2>
Hello world!
<h3>one-child1</h3>
I love dogs.
<h3>one-child2</h3>
I love cats.
<h2>two</h2>
This is pochika.
<h2>three</h2>
php
EOF;
        
        $plugin->handle($event);
        $this->assertNotFalse(strpos($post->content, '<div class="toc">'));
        
        // should not work if content has no {:TOC} tag
        $post->content = 'hello';
        $plugin->handle($event);
        $this->assertFalse(strpos($post->content, '<div class="toc">'));
    }
    
    public function testTocEscape()
    {
        $event = m::mock('App\Events\AfterConvert');
        $post = m::mock('Pochika\Entry\Post');

        $event->entry = $post;

        $plugin = PluginRepository::find('toc');

        $post->content = '\{:TOC}';

        $plugin->handle($event);
        $this->assertEquals('{:TOC}', $post->content);
    }

    public function tearDown()
    {
        m::close();
    }
    
//    // gfm - fenced code block
//    public function testFencedCodeBlock()
//    {
//        $plugin = new GfmPlugin();
//
//        $method = new ReflectionMethod($plugin, 'fencedCodeBlock');
//        $method->setAccessible(true);
//
//        $content = <<<EOF
//```
//test
//```
//EOF;
//
//        $res = $method->invoke($plugin, $content);
//
//        $this->assertRegExp('|<pre><code.*? >test</code></pre>|', $res);
//    }

    //// gfm - underscores in words
    //public function testUnderscoresInWords()
    //{
    //    $plugin = PluginRepository::find('gfm');
    //
    //    $entry = (object)['md' => 'wow_great_stuff'];
    //
    //    $plugin->convert($entry);
    //
    //    $this->assertEquals('wow\_great\_stuff', $entry->md);
    //}
    //
    //// gfm - autolink
    //public function testAutolink()
    //{
    //    $plugin = PluginRepository::find('gfm');
    //
    //    $entry = (object)['md' => "http://example.com/"];
    //
    //    $plugin->convert($entry);
    //
    //    $this->assertRegExp('|^\[http:.*\]\(http:.*\)$|', $entry->md);
    //}

}
