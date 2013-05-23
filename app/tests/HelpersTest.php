<?php

class HelpersTest extends TestCase {

    public function testD()
    {
        ob_start();
        d(true);
        $res = ob_get_clean();
        $this->assertEquals("bool(true)\n", $res);
    }

    public function testElement()
    {
        $arr = ['a' => 'blue', 'b' => 'red'];
        $this->assertEquals('blue', element('a', $arr));
        $this->assertEquals(null, element('c', $arr));
    }

    public function testBench()
    {
        $res = bench(function(){
            file_exists(__FILE__);
        }, 10);
        $this->assertTrue(is_float($res));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBenchArg2Exception()
    {
        bench(function(){}, 'a');
    }

    #todo
//    /**
//     * @expectedException ErrorException
//     */
//    public function testBenchError()
//    {
//        try {
//            bench(0);
//        } catch (Exception $e) {
//            $this->fail();
//        }
//    }

    public function testIsAssoc()
    {
        $arr = ['a' => 0, 'b' => 1];
        $this->assertTrue(is_assoc($arr));

        $arr = ['a', 'b', 'c'];
        $this->assertFalse(is_assoc($arr));
    }

	public function testIsCli()
	{
        $this->assertTrue(is_cli());
    }

    public function testToBool()
    {
        $this->assertTrue(bool('on'));
        $this->assertTrue(bool('true'));
        $this->assertTrue(bool('1'));
        $this->assertTrue(bool('yes'));

        $this->assertFalse(bool('off'));
        $this->assertFalse(bool('false'));
        $this->assertFalse(bool('0'));
        $this->assertFalse(bool('no'));
        $this->assertFalse(bool('none'));
        $this->assertFalse(bool('null'));
        $this->assertFalse(bool(false));

        $this->assertEquals(null, bool('hi'));
        $this->assertEquals(null, bool(''));
        $this->assertEquals(null, bool(null));
    }

}
