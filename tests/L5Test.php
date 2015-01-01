<?php

use Illuminate\Support\Facades\Cache;

class L5Test extends TestCase {

    public function tearDown()
    {
        //Cache::flush();
    }

    public function testEnvIsAlwaysTesting()
    {
        putenv('APP_ENV=local');
        $this->assertEquals('local', env('APP_ENV'));
        $this->assertStringStartsWith('tests', Config::get('pochika.config_path'));
    }

    //public function testCachePutCauseException()
    //{
    //    Cache::put('key', 'value', 1);
    //    //dd(Cache::get('key'));
    //}

    ///**
    // * @expectedException InvalidArgumentException
    // */
    //public function testCacheRememberCauseException()
    //{
    //    Cache::remember('key', 'value');
    //}

    //public function testDebugbarIsAlwaysEnabled()
    //{
    //    //$this->assertNotEquals(env('APP_DEBUG'), Debugbar::isEnabled());
    //}

}
