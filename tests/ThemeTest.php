<?php

class ThemeTest extends TestCase {

    public function testName()
    {
        $this->assertEquals('test', Theme::name());
        $this->assertEquals(Conf::get('theme'), Theme::name());
    }

    public function testCheck()
    {
        $this->assertTrue(Theme::check());
    }

    public function testExists()
    {
        $this->assertTrue(Theme::exists());
    }

    // #todo
    //public function testCheckAssetsLink()
    //{
    //    unlink(public_path('assets'));
    //    $this->assertFalse(Theme::hasAssetsLink());
    //
    //    $this->assertTrue(Theme::createAssetsLink());
    //    $this->assertTrue(Theme::hasAssetsLink());
    //}

}
