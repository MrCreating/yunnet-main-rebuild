<?php

namespace unt\objects;

use phpseclib3\Crypt\EC\BaseCurves\Base;

abstract class BaseAPIMethod extends BaseObject
{
    // настройки и редактирование профиля
    const GROUP_SETTINGS = 64;

    // создание и изменение записей и лайков
    const GROUP_WALL     = 32;

    // доступ к аккаунту в оффлайн
    const GROUP_ONLINE   = 16;

    // действия с сообщениями
    const GROUP_MESSAGES = 8;

    // просмотр и изменение друзей
    const GROUP_FRIENDS  = 4;

    // минимальный набор прав (просмотр профилей и получение текущего пользователя)
    const GROUP_PUBLIC   = 2;

    // без прав. Отключение авторизации для вызова метода
    const GROUP_DEFAULT  = 0;

    //////////////////////////////////
    protected string $method_name;

    protected int $method_permissions_group;

    protected BaseAPIMethodParams $params;

    public function __construct(string $method_name, int $method_permissions_group, ?BaseAPIMethodParams $params = NULL)
    {
        parent::__construct();
        $this->protect();

        $this->method_name              = $method_name;
        $this->method_permissions_group = $method_permissions_group;
        $this->params                   = $params ?: BaseAPIMethodParams::create();
    }

    public function getName (): string
    {
        return $this->method_name;
    }

    public function getPermissionsLevel (): int
    {
        return $this->method_permissions_group;
    }

    public function getParams (): BaseAPIMethodParams
    {
        return $this->params;
    }

    /**
     * Запуск метода
     * Для отдачи результата вызывать callback функцию, если передана.
     */
    abstract public function run (?Context $context, ?callable $callback = NULL): BaseAPIMethod;
}