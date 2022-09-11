<?php

namespace unt\objects;

use unt\exceptions\EntityNotFoundExceptiom;

abstract class Entity extends BaseObject
{
    protected int $id;

    protected string $type;

    public function __construct()
    {
        parent::__construct();
        $this->protect();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType (): string
    {
        return $this->type;
    }

    public abstract function toArray (array $fields = []): array;

    ///////////////////////////////////////////
    public static function findById (int $id): ?Entity
    {
        try {
            return $id > 0 ? new User($id) : new Bot($id);
        } catch (EntityNotFoundExceptiom $e) {
            return NULL;
        }
    }
}