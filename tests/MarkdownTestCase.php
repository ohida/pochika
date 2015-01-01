<?php

class MarkdownTestCase extends TestCase {

    public function testAll()
    {
        $this->testConvert();
        $this->testConvertNewLine();
        $this->testConvertNewLineMultiByte();
        $this->testConvertAutoLink();
        $this->testMultipleUnderscoresInWords();
    }

    public function testConvert()
    {
        $md = <<<EOF
#hello

hello world

- aaa
- bbb
- ccc
EOF;
        $html = $this->markdown->run($md);
        $this->assertRegExp('|<h1>hello</h1>|', $html);
        $this->assertRegExp('|<p>hello world</p>|', $html);
        $this->assertRegExp('|<li>aaa</li>|', $html);
    }

    public function testConvertNewLine()
    {
        $md = "one  \ntwo  \nthree";
        $html = $this->markdown->run($md);
        $this->assertRegExp('|<p>one<br\s?/?>|', $html);
    }

    public function testConvertNewLineMultiByte()
    {
        $md = "い  \nろ  \nは";
        $html = $this->markdown->run($md);
        $this->assertRegExp('|<p>い<br\s?/?>|', $html);
    }

    public function testConvertAutoLink()
    {
        $md = "http://www.github.com/";
        $html = $this->markdown->run($md);
        $this->assertRegExp('/<a href=/', $html);
    }

    public function testMultipleUnderscoresInWords()
    {
        $md = 'one_two_three';
        $html = $this->markdown->run($md);
        $this->assertRegExp('/one(&#95;|_)two(&#95;|_)three/', $html);
    }

    public function testConvertAutolinkCite()
    {
        $md = <<<EOF
> quote
>
> <cite>http://www.google.com/</cite>
EOF;
        $html = $this->markdown->run($md);
        $this->assertRegExp('|<cite><a href=|', $html);
        $this->assertRegExp('|</a></cite>|', $html);
    }

    public function testConvertNewlineInBlockquote()
    {
        $md = "> one  \n> two  \n> three";
        $html = $this->markdown->run($md);
        $this->assertRegExp('|<p>one<br\s?/?>\s*two<br\s?/?>\s*three</p>|', $html);
    }

    public function testConvertNewlineInBlockquoteMultiByte()
    {
        $md = "> い  \n> ろ  \n> は";
        $html = $this->markdown->run($md);
        $this->assertRegExp('|<p>い<br\s?/?>\s*ろ<br\s?/?>\s*は</p>|', $html);
    }

    public function testBlockquoteSingleLine()
    {
        $md = '> hello';
        $html = $this->markdown->run($md);

        $this->assertRegExp('|<blockquote>.*hello.*</blockquote>|s', $html);
        $this->assertFalse(strpos('<br', $html));
    }

    public function testUrlInCodeBlock()
    {
        $md = <<<EOF
```
http://www.google.com/
```
EOF;
        $html = $this->markdown->run($md);

        $this->assertRegExp('|google\.com/\n?</code>|', $html);
    }

    public function testEm()
    {
        $res = $this->markdown->run('_a_');
        $this->assertRegExp('|<em>a</em>|', $res);

        $res = $this->markdown->run('_日本_');
        $this->assertRegExp('|<em>日本</em>|', $res);
    }

}
