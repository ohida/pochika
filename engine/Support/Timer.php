<?php

namespace Pochika\Support;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Timer
{
    protected $logger = null;
    protected $path = null;
    protected $enabled = false;
    protected $timers = [];
    protected $start_time = null;

    public function __construct()
    {
        if ('testing' == env('APP_ENV')) {
            $this->enabled = false;
        } else {
            $this->enabled = true;
        }

        $this->path = storage_path().'/timer.log';
        $this->logger = $this->makeLogger($this->path);

        $this->booted = true;
    }

    protected function makeLogger($path)
    {
        $logger = new Logger('timer');
        $stream = new StreamHandler($path, Logger::DEBUG);
        $formatter = new \Monolog\Formatter\LineFormatter('%datetime% %message%'.PHP_EOL, 'H:i:s');
        $stream->setFormatter($formatter);
        $logger->pushHandler($stream);

        return $logger;
    }

    public function start()
    {
        if (!$this->enabled) {
            return false;
        }

        $this->write('Boot', microtime(true) - LARAVEL_START);
        $this->start_time = microtime(true);
    }

    public function stop()
    {
        if (!$this->enabled) {
            return false;
        }

        $this->write('Total', microtime(true) - $this->start_time);

        $fp = fopen($this->path, 'a+');
        fwrite($fp, "\n");
        fclose($fp);
    }

    public function measure($name, callable $f, $count = 1)
    {
        if (!$this->enabled) {
            return false;
        }

        if (!is_int($count)) {
            throw new InvalidArgumentException('Count must be integer');
        }

        $s = microtime(true);
        for ($i = 0; $i < $count; $i ++) {
            call_user_func($f);
        }

        $time = microtime(true) - $s;

        $this->write($name, $time);
    }

    public function startMeasure($name)
    {
        if (!$this->enabled) {
            return false;
        }

        $this->timers[$name] = microtime(true);
    }

    public function stopMeasure($name)
    {
        if (!$this->enabled) {
            return false;
        }

        if (isset($this->timers[$name])) {
            $time = microtime(true) - $this->timers[$name];
            unset($this->timers[$name]);
            $this->write($name, $time);
        }
    }

    public function write($name, $time)
    {
        if (!$this->enabled) {
            return false;
        }

        $time_str = sprintf('%7s', sprintf('%.2f', $time * 1000));

        $this->logger->debug(sprintf('%s %s', $time_str, $name));
    }
}
