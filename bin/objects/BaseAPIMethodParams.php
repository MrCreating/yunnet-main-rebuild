<?php

namespace unt\objects;

use phpseclib3\Crypt\EC\BaseCurves\Base;
use unt\exceptions\APIException;

class BaseAPIMethodParams extends BaseObject
{
    const PARAM_INT    = 2;
    const PARAM_STRING = 4;
    const PARAM_JSON   = 8;
    const PARAM_BOOL   = 16;

    /////////////////////////////////
    private array $params = [];

    private array $values = [];

    public function __construct()
    {
        parent::__construct();
        $this->protect();
    }

    public function addParam (string $param_name, int $param_type, bool $required = false): BaseAPIMethodParams
    {
        $this->params[$param_name] = [
            'type'     => $param_type,
            'required' => $required
        ];

        return $this;
    }

    /**
     * @throws APIException
     */
    public function getValues (): array
    {
        $params = $this->getParams();
        $result = [];

        $request_data = \UntEngine::get()->getRequest();
        foreach ($params as $param_name => $param_data) {
            $type     = $param_data['type'];
            $required = $param_data['required'];
            $value    = $request_data[$param_name];

            if (!isset($value) && $required)
                throw new APIException('Some parameters was missing or invalid: ' . $param_name . ' is required', -5);

            if (
                ($type == self::PARAM_JSON && !($value = json_decode($value))) ||
                ($type == self::PARAM_INT && !ctype_digit($value))
            ) throw new APIException('Some parameters was invalid type: ' . $param_name . ' is invalid data type', -6);

            $result[$param_name] = ($type == self::PARAM_INT) ? ((int) $value) : $value;
        }

        return $result;
    }

    public function getParams (): array
    {
        return $this->params;
    }

    ///////////////////////////////////
    public static function create (): BaseAPIMethodParams
    {
        return new self;
    }
}