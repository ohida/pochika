<?php

class PochikaPublishAssetsCommandTest extends TestCase {

    protected $dummyAssetsPath;

    public function setUp()
    {
        $this->dummyAssetsPath = sprintf("%s/source/themes/dummy/assets", base_path());
        $this->cleanUp();
    }

    public function tearDown()
    {
        $this->cleanUp();
    }

    public function testPublishAssets()
    {
        mkdir($this->dummyAssetsPath, 0777, true);
        Conf::set('theme', 'dummy');

        Artisan::call('pochika:publish_assets');

        $assetsPath = sprintf("%s/public/assets", base_path());
        $check = readlink($assetsPath);

        $this->assertThat($check, $this->equalTo($this->dummyAssetsPath));
    }

    /**
     * @expectedException Pochika\Exception\NotFoundException
     */
    public function testUnknownAssets()
    {
        Conf::set('theme', 'unknown');
        Artisan::call('pochika:publish_assets');
    }

    protected function cleanUp()
    {
        if (file_exists($this->dummyAssetsPath)) {
            rmdir($this->dummyAssetsPath);
        }
    }


}