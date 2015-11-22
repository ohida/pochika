<?php

class CacheTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Cache::flush();
    }

    public function testRememberForever()
    {
        //Cache::rememberForever('key', 'test');
        //$this->assertEquals('test', Cache::get('key'));

        $value = ['a', 'b', 'c'];
        Cache::rememberForever('key', function () use ($value) {
            return $value;
        });
        $this->assertEquals($value, Cache::get('key'));

        Cache::rememberForever('key', function () use ($value) {
            return [];
        });
        $this->assertEquals($value, Cache::get('key'));
    }

    public function testHas()
    {
        $this->assertFalse(Cache::has('key'));
        Cache::put('key', 'value', 1);
        $this->assertTrue(Cache::has('key'));
    }

    public function testPut()
    {
        Cache::put('key', 'value', 1);
        $this->assertEquals('value', Cache::get('key'));
    }
}
