<?php

class MarkdownTest extends TestCase {

    public function testFacadeRoot()
    {
        $class = get_class(Markdown::getFacadeRoot());
        if ('sundown' == Conf::get('markdown')) {
            $this->assertEquals('Pochika\Markdown\Sundown', $class);
        } else {
            $this->assertEquals('Pochika\Markdown\PHPMarkdownExtra', $class);
        }
    }

    public function testInstance()
    {
        $obj = App::make('markdown');
        if ('sundown' == Conf::get('markdown')) {
            $this->assertInstanceOf('Pochika\Markdown\Sundown', $obj);
        } else {
            $this->assertInstanceOf('Pochika\Markdown\PHPMarkdownExtra', $obj);
        }
    }

    /**
     * @expectedException LogicException
     */
    public function testInvalidParser()
    {
        Conf::set('markdown', 'invalid-parser');
        App::make('markdown');
    }

    /**
     * @requires extension sundown
     */
//    public function testSundown()
//    {
//        Conf::set('markdown', 'sundown');
//    }

}
