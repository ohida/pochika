<?php

use Mockery as m;

class RepositoryTest extends TestCase {

    protected $stub;
    protected $items = [
        'a' => ['a-1', 'a-2', 'a-3'],
        'b' => ['b-1', 'b-2', 'b-3'],
        'c' => ['c-1', 'c-2', 'c-3'],
    ];

    function setUp()
    {
        parent::setUp();

        $stub = $this->getMockForAbstractClass('Pochika\Repository\Repository');
        $stub->expects($this->any())
             ->method('collect')
             ->will($this->returnValue($this->items));
        $stub->expects($this->any())
             ->method('itemClass')
             ->will($this->returnValue('Test'));
        $this->stub = $stub;
    }

    function testLoad()
    {
        $this->stub->load();
        $this->assertEquals($this->items, $this->stub->items());
    }

    function testUnload()
    {
        $this->stub->load();
        $this->stub->unload();

        $this->assertNull($this->stub->all());
    }

    function testAll()
    {
        $this->stub->load();
        $res = $this->stub->all();
        
        $this->assertEquals('Pochika\Repository\EntryCollection', get_class($res));
    }

    function testFind()
    {
        $this->stub->load();
        $res = $this->stub->find('a');

        $this->assertEquals($this->items['a'], $res);
    }

    function testIndex()
    {
        $this->stub->load();
        
        $res = $this->stub->index('a');
        $this->assertEquals(0, $res);
        
        $res = $this->stub->index('b');
        $this->assertEquals(1, $res);
        
        $res = $this->stub->index('c');
        $this->assertEquals(2, $res);
    }

    /**
     * @expectedException NotFoundException
     */
    function testIndexInvalid()
    {
        $this->stub->load();
        $res = $this->stub->index('invalid-key');
    }

    function testKeys()
    {
        $this->stub->load();
        $res = $this->stub->keys();
        
        $this->assertEquals(array_keys($this->items), $res);
    }
    
}
