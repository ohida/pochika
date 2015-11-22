<?php

class L5Test extends TestCase
{
    public function tearDown()
    {
    }

    public function testEnvIsAlwaysTesting()
    {
        $this->assertEquals('testing', env('APP_ENV'));
        $this->assertStringStartsWith('tests', Config::get('pochika.config_path'));

        putenv('APP_ENV=local');
        $this->assertEquals('local', env('APP_ENV'));
        $this->assertStringStartsWith('tests', Config::get('pochika.config_path'));
    }

    //public function testDebugbarIsAlwaysEnabled()
    //{
    //    //$this->assertNotEquals(env('APP_DEBUG'), Debugbar::isEnabled());
    //}
}
