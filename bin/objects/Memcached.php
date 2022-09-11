<?php

namespace unt\objects;

class Memcached extends BaseObject
{
    protected \Memcached $client;

    public function __construct()
    {
        parent::__construct();
        $this->protect();
        $this->client = new \Memcached();
        $this->client->addServer('memcached', 11211);
    }

    public function getClient (): \Memcached
    {
        return $this->client;
    }
}