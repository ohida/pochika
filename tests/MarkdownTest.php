<?php

class MarkdownTest extends TestCase {

    public function testFacadeRoot()
    {
        $class = get_class(Markdown::getFacadeRoot());
        $this->assertEquals('Pochika\Markdown\Parsedown', $class);
    }

    //public function testFacadeRoot()
    //{
    //    $class = get_class(Markdown::getFacadeRoot());
    //    switch (Config::get('pochika.markdown_parser', 'parsedown')) {
    //        case 'parsedown':
    //            $this->assertEquals('Pochika\Markdown\Parsedown', $class);
    //            break;
    //        case 'markdown-extra':
    //            $this->assertEquals('Pochika\Markdown\PHPMarkdownExtra', $class);
    //            break;
    //        case 'sundown':
    //            $this->assertEquals('Pochika\Markdown\Sundown', $class);
    //            break;
    //        default:
    //            $this->fail('invalid markdown parser: '.$class);
    //    }
    //}

    //public function testInstance()
    //{
    //    $obj = App::make('markdown');
    //    switch (Config::get('pochika.markdown_parser', 'parsedown')) {
    //        case 'parsedown':
    //            $this->assertInstanceOf('Pochika\Markdown\Parsedown', $obj);
    //            break;
    //        case 'markdown-extra':
    //            $this->assertInstanceOf('Pochika\Markdown\PHPMarkdownExtra', $obj);
    //            break;
    //        case 'sundown':
    //            $this->assertInstanceOf('Pochika\Markdown\Sundown', $obj);
    //            break;
    //        default:
    //            $this->fail('invalid markdown parser: '.$class);
    //    }
    //}

    ///**
    // * @expectedException LogicException
    // */
    //public function testInvalidParser()
    //{
    //    Config::set('pochika.markdown_parser', 'invalid-parser');
    //    app('markdown');
    //}

    ///**
    // * @requires extension sundown
    // */
    //public function testSundown()
    //{
    //    Conf::set('markdown', 'sundown');
    //}

}
