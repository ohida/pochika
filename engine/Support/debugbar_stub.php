<?php

if (!function_exists('measure')) {
    function measure($name, callable $closure)
    {
        $closure();
    }
}

if (!function_exists('start_measure')) {
    function start_measure($name)
    {
    }
}

if (!function_exists('stop_measure')) {
    function stop_measure($name)
    {
    }
}
