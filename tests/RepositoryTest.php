<?php


class RepositoryTest extends TestCase
{
    protected $stub;
    protected $items = [
        'a' => ['a-1', 'a-2', 'a-3'],
        'b' => ['b-1', 'b-2', 'b-3'],
        'c' => ['c-1', 'c-2', 'c-3'],
    ];

    public function setUp()
    {
        parent::setUp();

        $stub = $this->getMockForAbstractClass('Pochika\Repository\Repository');
        $stub->expects($this->any())
             ->method('collect')
             ->will($this->returnValue(new Collection($this->items)));
        $this->stub = $stub;
    }

    public function testLoad()
    {
        $this->stub->load();
        $this->assertEquals($this->items, $this->stub->items());
    }

    public function testUnload()
    {
        $this->stub->load();
        $this->stub->unload();

        $this->assertNull($this->stub->all());
    }

    public function testAll()
    {
        $this->stub->load();
        $res = $this->stub->all();

        $this->assertEquals('Illuminate\Support\Collection', get_class($res));
    }

    public function testFind()
    {
        $this->stub->load();

        $res = $this->stub->find('a');
        $this->assertEquals($this->items['a'], $res);

        $res = $this->stub->find(0);
        $this->assertEquals($this->items['a'], $res);
    }

    /**
     * @expectedException LogicException
     */
    public function testFindInvalidKey()
    {
        $this->stub->load();
        $this->stub->find(-1);
    }

    /**
     * @expectedException LogicException
     */
    public function testFindInvalidKey2()
    {
        $this->stub->load();
        $this->stub->find(new stdClass);
    }

    /**
     * @expectedException LogicException
     */
    public function testFindInvalidKey3()
    {
        $this->stub->load();
        $this->stub->find('invalid-key');
    }

    public function testIndex()
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
    public function testIndexInvalid()
    {
        $this->stub->load();
        $res = $this->stub->index('invalid-key');
    }

    public function testKeys()
    {
        $this->stub->load();
        $res = $this->stub->keys();

        $this->assertEquals(array_keys($this->items), $res);
    }
}
