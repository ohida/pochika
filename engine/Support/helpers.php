<?php

if (!function_exists('element')) {
    function element($key, $arr, $default = null)
    {
        if (isset($arr[$key])) {
            return $arr[$key];
        }

        return $default;
    }
}

/**
 * @codeCoverageIgnore
 */
if (!function_exists('d')) {
    function d(...$args)
    {
        array_map(function ($x) {
            dump($x);
        }, $args);
    }
}

if (!function_exists('bench')) {
    function bench($closure, $count = 1)
    {
        if (!is_int($count)) {
            throw new InvalidArgumentException('Count must be integer');
        }

        if (is_array($closure)) {
            $results = [];
            foreach ($closure as $row) {
                $results[] = bench($row, $count);
            }

            return $results;
        }

        $s = microtime(true);
        for ($i = 0; $i < $count; $i ++) {
            call_user_func($closure);
        }

        return microtime(true) - $s;
    }
}

if (!function_exists('is_cli')) {
    function is_cli()
    {
        return 'cli' == php_sapi_name();
    }
}

if (!function_exists('is_assoc')) {
    function is_assoc($array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}

if (!function_exists('bool')) {
    function bool($val)
    {
        if (is_bool($val)) {
            return $val;
        }

        if (is_string($val)) {
            $val = strtolower($val);
        }

        $trues = ['true', 'on', '1', 'yes'];
        $falses = ['false', 'off', '0', 'no', 'none', 'null'];

        if (in_array($val, $trues)) {
            return true;
        } elseif (in_array($val, $falses)) {
            return false;
        }

        throw new \LogicException('cannot cast to boolean: '.$val);
        //return $val;
    }
}

if (!function_exists('root')) {
    function root()
    {
        return dirname(dirname(__DIR__));
    }
}

/**
 * @codeCoverageIgnore
 */
if (!function_exists('copy_r')) {
    function copy_r($src_dir, $dst_dir)
    {
        if (!is_dir($dst_dir)) {
            mkdir($dst_dir);
        }

        if (is_dir($src_dir)) {
            if ($dh = opendir($src_dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    if (is_dir($src_dir.'/'.$file)) {
                        copy_r($src_dir.'/'.$file, $dst_dir.'/'.$file);
                    } else {
                        copy($src_dir.'/'.$file, $dst_dir.'/'.$file);
                    }
                }
                closedir($dh);
            }
        }

        return true;
    }
}

if (!function_exists('source_path')) {
    function source_path($path = '')
    {
        if (is_array($path)) {
            $res = [];
            foreach ($path as $row) {
                $res[] = source_path($row);
            }
            return $res;
        }

        $path = path(Conf::get('source', 'source'), $path);

        if ($path[0] == '/' or $path[0] == '~') {
            return $path;
        } else {
            return path(app('path.base'), $path);
        }
    }
}

if (!function_exists('path')) {
    function path($dir, $path)
    {
        return rtrim($dir, '/').($path ? '/'.$path : '');
    }
}

if (!function_exists('theme_path')) {
    function theme_path($path = '')
    {
        return path(source_path('themes/'.Conf::get('theme')), $path);
    }
}

if (!function_exists('command_exists')) {
    function command_exists($cmd)
    {
        $ret = shell_exec("which $cmd");
        return empty($ret) ? false : true;
    }
}

if (!function_exists('open')) {
    function open($path)
    {
        $cmd = 'open';
        if (command_exists($cmd)) {
            $out = [];
            $val = null;
            exec(sprintf('%s "%s"', $cmd, $path), $out, $val);
            return $val == 0;
        }
    }
}
