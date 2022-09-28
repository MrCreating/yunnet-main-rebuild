<?php

use unt\exceptions\APIException;
use unt\objects\Context;

header('Content-Type: application/json');

// API exceptions handler
set_exception_handler(function (APIException $e) {
    die(json_encode([
        'error' => [
            'error_code' => $e->getCode(),
            'error_message' => $e->getMessage(),
        ],
        'params' => UntEngine::get()->getRequest()
    ]));
});

try {
    $context = Context::get();
} catch (\unt\exceptions\IncorrectSessionException $e) {
    throw new APIException('Authentication failed: token is invalid or not provided', -1);
} catch (\unt\exceptions\EntityNotFoundExceptiom $e) {
    throw new APIException('Requested entity not found or deleted', -2);
}

?>