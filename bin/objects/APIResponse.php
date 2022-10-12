<?php

namespace unt\objects;

class APIResponse extends BaseObject
{
    protected array $data;

    public function __construct(array $data = [])
    {
        parent::__construct();
        $this->protect();
        $this->data = $data;
    }

    public function show (): void
    {
        die(json_encode(['response' => $this->data]));
    }
}