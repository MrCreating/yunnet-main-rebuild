<?php

namespace unt\exceptions;

class FatalException extends BaseException
{
    public function __construct($message, $errfile, $errno, $errline, $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}