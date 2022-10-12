<?php

namespace unt\objects;

use unt\exceptions\APIException;

class API extends BaseObject
{
    protected string $method = '';

    protected array $methods = [];

    public function __construct(string $method)
    {
        parent::__construct();
        $this->protect();

        $this->method = $method;
    }

    public function getMethodObject (): BaseAPIMethod
    {
        $method_data = explode('.', $this->method, 2);

        $method_group = basename(strtolower($method_data[0]));
        $method_name  = basename(strtolower($method_data[1]));

        if (isset($this->methods[$method_group . '.' . $method_name]))
            return $this->methods[$method_group . '.' . $method_name];

        $path = __DIR__ . '/../../api/methods/' . $method_group . '/' . $method_name . '.php';
        if (file_exists($path)) {
            $methodObject = (include_once $path);
            if ($methodObject instanceof BaseAPIMethod && $methodObject->getName() === ($method_group . '.' . $method_name)) {
                $this->methods[$method_group . '.' . $method_name] = $methodObject;

                return $methodObject;
            }
        }

        throw new APIException('Method not found', -3);
    }
}