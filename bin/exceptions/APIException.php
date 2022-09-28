<?php

namespace unt\exceptions;

class APIException extends BaseException
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}