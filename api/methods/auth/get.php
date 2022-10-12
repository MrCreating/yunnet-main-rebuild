<?php

use unt\objects\BaseAPIMethod;
use unt\objects\Context;

return new class extends BaseAPIMethod
{
    public function __construct()
    {
        parent::__construct('auth.get', BaseAPIMethod::GROUP_DEFAULT, \unt\objects\BaseAPIMethodParams::create()
            ->addParam('login', \unt\objects\BaseAPIMethodParams::PARAM_STRING, true)
            ->addParam('password', \unt\objects\BaseAPIMethodParams::PARAM_STRING, true)
            ->addParam('app_id', \unt\objects\BaseAPIMethodParams::PARAM_INT, true)
        );
    }

    /**
     * @param Context|null $context
     * @param callable|null $callback
     * @return BaseAPIMethod
     */
    public function run(?Context $context, ?callable $callback = NULL): BaseAPIMethod
    {
        $params = $this->getParams()->getValues();

        return $this;
    }
};