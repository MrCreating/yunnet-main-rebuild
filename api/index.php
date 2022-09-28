<?php

use unt\objects\API;
use unt\objects\Context;
use unt\exceptions\APIException;

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: http://' . UntEngine::get()->getConfig()->getProjectDomain());
header('Content-Type: application/json');

// API exceptions handler
set_exception_handler(function (APIException $e) {
    die(json_encode([
        'error' => [
            'error_code' => $e->getCode(),
            'error_message' => $e->getMessage(),
        ],
        'params' => (object)UntEngine::get()->getRequest()
    ]));
});

$method = substr(UntEngine::get()->getRequestPage(), 1);
$api = new API($method);

try {
    $context = Context::get();
} catch (\unt\exceptions\IncorrectSessionException $e) {
    throw new APIException('Authentication failed: token is invalid or not provided', -1);
} catch (\unt\exceptions\EntityNotFoundExceptiom $e) {
    throw new APIException('Requested entity not found or deleted', -2);
}

?>