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
str-bool: true
str-on: on
num: 1
EOF;
        $res = SymfonyYaml::parse($yaml);

        $this->assertCount(6, $res);
        $this->assertTrue(isset($res['pet']));
        $this->assertEquals('dog', $res['pet']);
        $this->assertTrue(is_bool($res['str-bool']));
        $this->assertTrue(is_string($res['str-on']));
        $this->assertEquals(1, $res['num']);
    }

    public function testPeclParse()
    {
        if (!extension_loaded('yaml')) {
            return;
            //$this->markTestSkipped('yaml extension not loaded');
        }
        $yaml = <<<EOF
pet: dog
drink: coffee
fruilt: orange
str-bool: true
str-on: on
num: 1
EOF;
        $res = PeclYaml::parse($yaml);

        $this->assertCount(6, $res);
        $this->assertTrue(isset($res['pet']));
        $this->assertEquals('dog', $res['pet']);
        $this->assertTrue(is_bool($res['str-bool']));
        $this->assertTrue(is_bool($res['str-on']));
        $this->assertEquals(1, $res['num']);
    }

}
