<?php

use unt\exceptions\InvalidConfigException;
use unt\objects\BaseActivity;
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

    /**
     * @throws InvalidConfigException
     */
    public function run (\unt\objects\Config $config = NULL): void
    {
        $config = ($config ? $config : new Config());
        $this->config = $config;

        $result = $this->config->apply();
        if ($result) {
            $requested_domain = basename(explode('.', strtolower($_SERVER['HTTP_HOST']))[0]);

            $domains_list = $this->config->getDomainsList();
            $domain = in_array($requested_domain, $domains_list) ? $requested_domain : '';

            call_user_func($this->config->getMainRouter(), $domain);
        }
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