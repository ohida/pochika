<?php

use Pochika\Yaml\PeclYaml;
use Pochika\Yaml\SymfonyYaml;

class YamlTest extends TestCase {

    public function setUp()
    {
    }

    public function testSymfonyParse()
    {
        $yaml = <<<EOF
pet: dog
drink: coffee
fruilt: orange
EOF;
        $res = SymfonyYaml::parse($yaml);

        $this->assertCount(3, $res);
        $this->assertTrue(isset($res['pet']));
        $this->assertEquals('dog', $res['pet']);
    }

    public function testPeclParse()
    {
        if (!extension_loaded('yaml')) {
            $this->markTestSkipped('yaml extension not loaded');
        }
        $yaml = <<<EOF
pet: dog
drink: coffee
fruilt: orange
EOF;
        $res = PeclYaml::parse($yaml);

        $this->assertCount(3, $res);
        $this->assertTrue(isset($res['pet']));
        $this->assertEquals('dog', $res['pet']);
    }

}
