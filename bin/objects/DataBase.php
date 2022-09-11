<?php

namespace unt\objects;

class DataBase extends BaseObject
{
    public const EVENT_DATABASE_CONNECTED = 'event.database.connected';

    private \PDO $dbClient;

    public function __construct()
    {
        parent::__construct();
        $this->protect();

        $config = \UntEngine::get()->getConfig()->getDBData();

        $this->dbClient = new \PDO('mysql:host=' . $config->getHost() . ';port=' . $config->getPort(),
            $config->getUser(),
            $config->getPassword()
        );
    }

    public function getClient (): \PDO
    {
        return $this->dbClient;
    }

    // builds a query request
    public function call (string $query, array $params = []): ?array
    {
        $res = $this->getClient()->prepare($query);
        if ($res->execute($params))
        {
            $data = $res->fetchAll(\PDO::FETCH_ASSOC);
            if (!is_array($data)) return NULL;

            return $data;
        }

        return NULL;
    }

    ///////////////////////////
    public static function get (): DataBase
    {
        $inst =
            isset($_SERVER['currentDataBaseConnection']) && ($_SERVER['currentDataBaseConnection'] instanceof DataBase)
                ? $_SERVER['currentDataBaseConnection']
                : ($_SERVER['currentDataBaseConnection'] = new self());

        return $inst->emitEvent(self::EVENT_DATABASE_CONNECTED, []);
    }
}