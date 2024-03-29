<?php

namespace unt\objects;

use unt\exceptions\BaseException;
use unt\exceptions\FatalException;
use unt\exceptions\InvalidConfigException;

class Config extends BaseObject
{
    public const EVENT_BEFORE_APPLY  = 'event.apply.before';
    public const EVENT_AFTER_APPLY   = 'event.apply.after';
    public const EVENT_SHOWN_ERRORS  = 'event.errors.shown';
    public const EVENT_HIDDEN_ERRORS = 'event.errors.hidden';

    private array $config = [];

    private $dbData;

    /**
     * @var callable
     */
    private $main_domain_router;

    /**
     * @var array
     */
    private array $domains_list;
    /**
     * @var mixed
     */
    private $project_domain;
    /**
     * @var mixed
     */
    private $project_dev_domain;

    public function __construct(?array $config = NULL)
    {
        parent::__construct();

        if ($config == NULL)
            $config = require_once  __DIR__ . '/../config/main.php';

        $this->config = $config;
    }

    public function getProjectDomain (): string
    {
        $dev = !((int) getenv('UNT_PRODUCTION'));

        return $dev ? $this->project_dev_domain : $this->project_domain;
    }

    public function getMainRouter (): callable
    {
        return $this->main_domain_router;
    }

    public function getDomainsList (): array
    {
        return $this->domains_list;
    }

    /**
     * @throws InvalidConfigException
     */
    public function apply(): bool
    {
        $this->emitEvent(self::EVENT_BEFORE_APPLY, $this->config);

        if (!isset($this->config['db']))
            throw new InvalidConfigException("db sections must be present at the config");

        $this->dbData = new class ($this->config['db']) extends BaseObject {
            private string $host;
            private int $port;
            private string $user;
            private string $password;

            public function __construct (array $config)
            {
                parent::__construct();
                $this->protect();

                if (!(is_string($config['host'])))
                    throw new InvalidConfigException("DB host must be a string");
                if (!(is_int($config['port'])))
                    throw new InvalidConfigException("DB port must be a string");
                if (!(is_string($config['user'])))
                    throw new InvalidConfigException("DB user must be a string");
                if (!(is_string($config['password'])))
                    throw new InvalidConfigException("DB password must be a string");

                $this->host     = $config['host'];
                $this->port     = $config['port'];
                $this->user     = $config['user'];
                $this->password = $config['password'];
            }

            public function getHost (): string
            {
                return $this->host;
            }

            public function getPort (): int
            {
                return $this->port;
            }

            public function getUser (): string
            {
                return $this->user;
            }

            public function getPassword (): string
            {
                return $this->password;
            }
        };

        if (!isset($this->config['errors']))
            throw new InvalidConfigException("errors sections must be present at the config");

        if (isset($this->config['errors'][E_ALL]) && is_callable($this->config['errors'][E_ALL]))
            set_error_handler($this->config['errors'][E_ALL], E_ALL);
        if (isset($this->config['errors'][E_NOTICE]) && is_callable($this->config['errors'][E_NOTICE]))
            set_error_handler($this->config['errors'][E_NOTICE], E_NOTICE);
        if (isset($this->config['errors'][E_STRICT]) && is_callable($this->config['errors'][E_STRICT]))
            set_error_handler($this->config['errors'][E_STRICT], E_STRICT);
        if (isset($this->config['errors'][E_DEPRECATED]) && is_callable($this->config['errors'][E_DEPRECATED]))
            set_error_handler($this->config['errors'][E_DEPRECATED], E_DEPRECATED);

        if (isset($this->config['errors']['fatal']) && is_callable($this->config['errors']['fatal']))
            register_shutdown_function(function () {
                $errfile = "unknown file";
                $errstr  = "shutdown";
                $errno   = E_CORE_ERROR;
                $errline = 0;

                $error = error_get_last();

                if($error !== NULL) {
                    $errno   = $error["type"];
                    $errfile = $error["file"];
                    $errline = $error["line"];
                    $errstr  = $error["message"];

                    call_user_func($this->config['errors']['fatal'], new FatalException($errstr, $errfile, $errno, $errline));
                }
            });

        if (!isset($this->config['router']) || !is_callable($this->config['router']))
            throw new InvalidConfigException("The domain router must be provided and it must be a function.");

        $domains_list = $this->config['domains'];
        $this->domains_list = $domains_list;

        $main_domain_router = $this->config['router'];
        $this->main_domain_router = $main_domain_router;

        $project_domain     = $this->config['domain'];
        $project_dev_domain = $this->config['dev_domain'];

        $this->project_domain     = $project_domain;
        $this->project_dev_domain = $project_dev_domain;

        $this->emitEvent(self::EVENT_AFTER_APPLY, $this->config);

        return true;
    }

    public function showErrors (): Config
    {
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        $this->emitEvent(self::EVENT_SHOWN_ERRORS, []);
        return $this;
    }

    public function hideErrors (): Config
    {
        error_reporting(0);
        ini_set("display_errors", 0);
        $this->emitEvent(self::EVENT_HIDDEN_ERRORS, []);
        return $this;
    }

    public function getDBData ()
    {
        return $this->dbData;
    }
}