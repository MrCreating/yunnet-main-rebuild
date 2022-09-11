<?php

namespace unt\exceptions;

class BaseException extends \Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function log (): void
    {

    }
}