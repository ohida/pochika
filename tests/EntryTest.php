<?php

class EntryTest extends TestCase
{
    protected $entry;

    public function factory(...$args)
    {
        if (!$args) {
            $args = [source_path('posts/2013-03-03-hello.md')];
        }

        $stub = $this->getMockForAbstractClass('Pochika\Entry\Entry', $args);
        //$stub->expects($this->any())
        //     ->method('collect')
        //     ->will($this->returnValue($this->items));
        return $stub;
    }

    public function testInit()
    {
        $entry = $this->factory();

        $this->assertObjectHasAttribute('key', $entry);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testInvalidFile()
    {
        $this->factory('invalid-file-path');
    }

    //function testOffsetGet()
    //{
    //    $entry = $this->factory();
    //
    //    d($entry['key']);
    //}

    public function testGetKey()
    {
        $entry = $this->factory();

        $this->assertEquals('2013-03-03-hello', $entry->key);
        $this->assertEquals('2013-03-03-hello', $entry['key']);
    }

    public function testGetDate()
    {
        $entry = $this->factory();

        $this->assertEquals($entry->date, $entry['date']);
    }

    public function testNonExistKey()
    {
        $entry = $this->factory();
        $this->assertNull($entry->something);
        $this->assertNull($entry['something']);
    }

    public function testSet()
    {
        $entry = $this->factory();

        $entry['hello'] = 'world';
        $this->assertEquals('world', $entry['hello']);
        $this->assertEquals('world', $entry->hello);

        $entry->fruit = 'apple';
        $this->assertEquals('apple', $entry['fruit']);
        $this->assertEquals('apple', $entry->fruit);
    }

    public function testUnset()
    {
        $entry = $this->factory();

        $entry['test'] = 'test';
        unset($entry['test']);

        $this->assertFalse(isset($entry['test']));
        $this->assertFalse(isset($entry->test));
    }
}
