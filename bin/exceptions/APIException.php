<?php

namespace unt\exceptions;

use UntEngine;

class APIException extends BaseException
{
    /**
     * @var array|mixed
     */
    private ?array $additional_data = NULL;

    public function __construct($message = "", $code = 0, $additional_data = NULL, $previous = NULL)
    {
        parent::__construct($message, $code, $previous);

        $this->additional_data = $additional_data;
    }

    public function toArray (): array
    {
        $res = [
            'error' => [
                'error_code' => $this->getCode(),
                'error_message' => $this->getMessage()
            ],
            'params' => (object)UntEngine::get()->getRequest(),
        ];

        if ($this->additional_data !== NULL)
            $res['data'] = $this->additional_data;

        return $res;
    }
}