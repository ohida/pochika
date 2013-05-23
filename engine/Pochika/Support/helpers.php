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
    function d($msg = null, $exit = false)
    {
        if (is_cli()) {
            var_dump($msg);
        } else {
            echo '<pre>';
            var_dump($msg);
            echo '</pre>';
        }
        if ($exit) exit();
    }
}

if (!function_exists('bench')) {
    function bench(callable $f, $count = 1)
    {
        if (!is_int($count)) {
            throw new InvalidArgumentException('Count must be integer');
        }

        $s = microtime(true);
        for ($i = 0; $i < $count; $i ++) {
            call_user_func($f);
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
        } else if (in_array($val, $falses)) {
            return false;
        }

        return null;
    }
}

if (!function_exists('root')) {
    function root()
    {
        return dirname(dirname(dirname(__DIR__)));
    }
}

/**
 * @codeCoverageIgnore
 */
if (!function_exists('env')) {
    function env()
    {
        if (file_exists($env_file = root().'/env.php')) {
            return include $env_file;
        }

        return element('LARAVEL_ENV', $_SERVER, 'local');
    }
}
