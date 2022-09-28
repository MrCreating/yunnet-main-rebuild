<?php

namespace unt\objects;

use unt\exceptions\EntityNotFoundExceptiom;
use unt\exceptions\IncorrectSessionException;
use unt\lang\Language;
use unt\lang\RussianLanguage;

class Context extends BaseObject
{
    protected string $token;

    protected Memcached $memcached;
    protected DataBase $dataBase;

    protected Entity $entity;

    /**
     * добавьте _entId к токену, дабы получить сущность
     * добавьте _perms к токену, дабы получить права
     * @throws IncorrectSessionException
     * @throws EntityNotFoundExceptiom
     */
    public function __construct()
    {
        parent::__construct();
        $this->protect();

        $request_data = \UntEngine::get()->getRequest();

        $token = $request_data['token'] ?? $_SESSION['token'];
        if (!$token)
            throw new IncorrectSessionException("Token must be provided as a parameter or in session");

        $this->dataBase  = DataBase::get();
        $this->memcached = Memcached::get();

        $this->token = $token;

        $user_id = (int)$this->memcached->getClient()->get($this->token . '_entId');
        if ($user_id == 0)
            throw new IncorrectSessionException("User token is invalid");

        $user = Entity::findById($user_id);
        if (!$user)
            throw new EntityNotFoundExceptiom("User entity is invalid");

        $this->entity = $user;
    }

    public function getEntity (): Entity
    {
        return $this->entity;
    }

    public function getToken (): string
    {
        return $this->token;
    }

    ///////////////////////////////
    public static function get (): Context
    {
        return isset($_SERVER['userContext']) && $_SERVER['userContext'] instanceof Context ?
            $_SERVER['userContext'] : ($_SERVER['userContext'] = new self());
    }
}