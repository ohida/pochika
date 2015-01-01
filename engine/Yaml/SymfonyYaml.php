<?php namespace Pochika\Yaml;

use Symfony\Component\Yaml\Yaml;

class SymfonyYaml implements YamlInterface {

    public static function parse($yaml)
    {
        return Yaml::parse($yaml);
    }

//    public static function emit($data)
//    {
//        return Yaml::dump($data);
//    }

}
