<?php

class HelpersTest extends TestCase {

    //public function testD()
    //{
    //    dump(true);
    //
    //    ob_start();
    //    d(true);
    //    $res = ob_get_clean();
    //    $this->assertEquals("true\n", $res);
    //
    //    ob_start();
    //    d(true, "a");
    //    $res = ob_get_clean();
    //    $this->assertEquals("true\nstring(1) \"a\"\n", $res);
    //}

    public function testElement()
    {
        $arr = ['a' => 'blue', 'b' => 'red'];
        $this->assertEquals('blue', element('a', $arr));
        $this->assertEquals(null, element('c', $arr));
    }

    //public function testBench()
    //{
    //    $res = bench(function(){
    //        file_exists(__FILE__);
    //    }, 10);
    //    $this->assertTrue(is_float($res));
    //}

    public function testBench()
    {
        $res = bench(function(){
            file_exists(__FILE__);
        }, 10);
        $this->assertTrue(is_float($res));

        $res = bench([
            function(){
                file_exists(__FILE__);
            },
            function() {
                file_exists(__FILE__);
            },
        ], 100);
        $this->assertEquals(2, count($res));
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

    }

    /**
     * @expectedException LogicException
     */
    public function testToBoolException1()
    {
        $this->assertEquals(null, bool('hi'));
        //$this->assertEquals(null, bool(''));
        //$this->assertEquals(null, bool(null));
    }

    /**
     * @expectedException LogicException
     */
    public function testToBoolException2()
    {
        $this->assertEquals(null, bool(''));
    }

    /**
     * @expectedException LogicException
     */
    public function testToBoolException3()
    {
        $this->assertEquals(null, bool(null));
    }

    public function testSourcePath()
    {
        $this->assertStringEndsWith('source/posts', source_path('posts'));
        $this->assertStringEndsWith('source/a/b/c', source_path('a/b/c'));

        $paths = source_path(['posts', 'pages']);
        $this->assertCount(2, $paths);
        $this->assertStringEndsWith('source/posts', $paths[0]);
        $this->assertStringEndsWith('source/pages', $paths[1]);
    }

    public function testThemePath()
    {
        $this->assertStringEndsWith('themes/test', theme_path());
        $this->assertStringEndsWith('test/assets', theme_path('assets'));
    }

    //public function testUrl()
    //{
    //    $this->assertEquals('http://localhost/', url('/'));
    //}
}
