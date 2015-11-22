<?php

namespace Pochika\Exception;

class NotInitializedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Pochika is not initialized');
    }
}
