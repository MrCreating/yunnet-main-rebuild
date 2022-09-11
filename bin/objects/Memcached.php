<?php

namespace unt\objects;

class Memcached extends BaseObject
{
    const EVENT_MEMCACHED_CONNECTED = 'event.memcached.connected';

    protected \Memcached $client;

    public function __construct()
    {
        parent::__construct();
        $this->protect();
        $this->client = new \Memcached();
        $this->client->addServer('memcached', 11211);
        $this->emitEvent(self::EVENT_MEMCACHED_CONNECTED, []);
    }

    public function getClient (): \Memcached
    {
        return $this->client;
    }

    ///////////////////////////////
    public static function get()
    {
        return isset($_SERVER['currentMCClient']) && ($_SERVER['currentMCClient'] instanceof Memcached) ?
            $_SERVER['currentMCClient'] :
            ($_SERVER['currentMCClient'] = new self());
    }
}