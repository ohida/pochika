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
        $html = $this->markdown->convert($md);
        $this->assertRegExp('|<h1>hello</h1>|', $html);
        $this->assertRegExp('|<p>hello world</p>|', $html);
        $this->assertRegExp('|<li>aaa</li>|', $html);
    }

    public function testConvertNewLine()
    {
        $md = <<<EOF
one
two
three
EOF;
        $html = $this->markdown->convert($md);
        $this->assertRegExp('|<p>one<br\s?/?>|', $html);
    }

    public function testConvertNewLineMultiByte()
    {
        $md = <<<EOF
い
ろ
は
EOF;
        $html = $this->markdown->convert($md);
        $this->assertRegExp('|<p>い<br\s?/?>|', $html);
    }

    public function testConvertAutoLink()
    {
        $md = <<<EOF
http://www.github.com/
EOF;
        $html = $this->markdown->convert($md);
        $this->assertRegExp('|<a href=|', $html);
    }

    public function testMultipleUnderscoresInWords()
    {
        $md = 'one_two_three';
        $html = $this->markdown->convert($md);
        $this->assertRegExp('/one(&#95;|_)two(&#95;|_)three/', $html);
    }

    public function testConvertAutolinkCite()
    {
        $md = <<<EOF
> quote
>
> <cite>http://www.google.com/</cite>
EOF;
        $html = $this->markdown->convert($md);
        $this->assertRegExp('|<cite><a href=|', $html);
        $this->assertRegExp('|</a></cite>|', $html);
    }

    public function testConvertNewlineInBlockquote()
    {
        $md = <<<EOF
> one
> two
> three
EOF;
        $html = $this->markdown->convert($md);
        $this->assertRegExp('|<p>one<br\s?/?>\s*two<br\s?/?>\s*three</p>|', $html);
    }

    public function testConvertNewlineInBlockquoteMultiByte()
    {
        $md = <<<EOF
> い
> ろ
> は
EOF;
        $html = $this->markdown->convert($md);
        $this->assertRegExp('|<p>い<br\s?/?>\s*ろ<br\s?/?>\s*は</p>|', $html);
    }

    public function testBlockquoteSingleLine()
    {
        $md = '> hello';
        $html = $this->markdown->convert($md);

        $this->assertRegExp('|<blockquote>.*hello.*</blockquote>|s', $html);
        $this->assertFalse(strpos('<br', $html));
    }

}
