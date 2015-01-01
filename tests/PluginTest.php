<?php

class PluginTest extends TestCase {

    public function testEmojiPlugin()
    {
        $plugin = PluginRepository::find('emoji');

        $post = (object)['content' => ':octocat:'];
        $params = (object)['entry' => &$post];

        $plugin->convert($params);

        $this->assertRegExp('/<img .*?>/', $post->content);
    }

//    public function testTocPlugin()
//    {
//        $plugin = PluginRepository::find('toc');
//
//        $html = <<<EOF
//<h2>one</h2>
//Hello world!
//
//<h2>two</h2>
//This is pochika.
//EOF;
//
//        $post = (object)['content' => $html];
//        $params = (object)['entry' => &$post];
//
//        $plugin->handle($params);
//
//    }
    
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
