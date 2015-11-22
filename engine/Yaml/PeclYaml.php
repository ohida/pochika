<?php

namespace Pochika\Yaml;

class PeclYaml implements YamlInterface
{
    public static function parse($yaml)
    {
        return yaml_parse($yaml);
    }

//    public static function emit($data)
//    {
//        return yaml_emit($data);
//    }
}
