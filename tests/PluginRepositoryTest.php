<?php

class PluginRepositoryTest extends TestCase {

    const PLUGIN_COUNT = 3;

    public function testLoad()
    {
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
