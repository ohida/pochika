<?php

class PluginTest extends TestCase {

    public function setUp()
    {
    }

    public function testEmojiPlugin()
    {
        $plugin = PluginRepository::find('emoji');

        $post = (object)['content' => ':octocat:'];
        $params = (object)['entry' => &$post];

        $plugin->convert($params);

        $this->assertRegExp('/<img .*?>/', $post->content);
    }

    public function testFencedCodeBlock()
    {
        $plugin = new GfmPlugin();

        $method = new ReflectionMethod($plugin, 'fencedCodeBlock');
        $method->setAccessible(true);

        $content = <<<EOF
```
test
```
EOF;

        $res = $method->invoke($plugin, $content);

        $this->assertRegExp('|<pre><code.*?>test</code></pre>|', $res);
    }

}
