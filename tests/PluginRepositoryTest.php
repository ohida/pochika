<?php

class PluginRepositoryTest extends TestCase
{
    const PLUGIN_COUNT = 3;

    //public function setUp()
    //{
    //    $this->refreshApplication();
    //}

    public function testLoad()
    {
        //PluginRepository::load();
        $plugins = PluginRepository::all();
        $this->assertCount(self::PLUGIN_COUNT, $plugins->all());
    }

    public function testLoadOnCache()
    {
        Conf::set('cache', true);
        PluginRepository::unload();
        PluginRepository::load();
        PluginRepository::unload();
        PluginRepository::load();
        $plugins = PluginRepository::all();
        $this->assertCount(self::PLUGIN_COUNT, $plugins->all());
    }

    public function testFind()
    {
        $plugin = PluginRepository::find(0);
        $this->assertInstanceOf('Plugin', $plugin);
    }

    /**
     * @expectedException NotFoundException
     */
    public function testFindNotFound()
    {
        PluginRepository::find(99);
    }
}
