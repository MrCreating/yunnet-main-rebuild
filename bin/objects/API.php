<?php

namespace unt\objects;

class API extends BaseObject
{
    public function __construct(string $method)
    {
        parent::__construct();

        $this->protect();
    }
}