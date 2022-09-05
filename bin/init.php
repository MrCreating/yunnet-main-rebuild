<?php

use unt\objects\Config;

/**
 * Класс, инициализирубщий среду.
 * Один класс - один экземпляр контекста!
 * Таких можно создать сколь угодно.
 */

class UntEngine
{
    private ?Config $config = NULL;

    /**
     * @throws Exception
     */
    public function __construct ()
    {
        spl_autoload_register(function ($name) {
            $parts = array_slice(explode('\\', $name), 1);

            require_once __DIR__ . '/' . implode('/', $parts) . '.php';
        });
    }

    public function run (\unt\objects\Config $config = NULL): void
    {
        $config = ($config ? $config : new Config());
        $this->config = $config;

        $this->config->apply();
    }

    public function getConfig (): Config
    {
        return $this->config;
    }

    ///////////////////////////////////////////
    public static function get (): UntEngine
    {
        return
            isset($_SERVER['currentContext']) && ($_SERVER['currentContext'] instanceof UntEngine)
                ? $_SERVER['currentContext']
                : ($_SERVER['currentContext'] = new self());
    }
}

?>