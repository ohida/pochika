<?php namespace Pochika\Exception;

class NotFoundException extends \Exception {

    public function __construct($message)
    {
        parent::__construct('Not Found Exception:: '.$message);
    }

}
