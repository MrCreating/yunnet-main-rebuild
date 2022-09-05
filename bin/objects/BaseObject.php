<?php

namespace unt\objects;

use unt\exceptions\InvalidPropertyException;

class BaseObject
{
    // Названия событий лучше хранить так
    public const EVENT_PROTECTION_ENABLED  = 'event.protection.enabled';
    public const EVENT_PROTECTION_DISABLED = 'event.protection.disabled';

    /**
     * Определяет защиту объекта от добавления или получения не заданных полей
     */
    private bool $__sealedObject = false;

    /**
     * Слушатели событий
     */
    private array $__eventHandlers = [];

    public function __construct()
    {
    }

    /**
     * Добавляет прослушку событий на изменение объекта
     */
    public function on (string $eventName, callable $callback): BaseObject
    {
        if (!isset($this->__eventHandlers[$eventName]))
            $this->__eventHandlers[$eventName] = [];

        $this->__eventHandlers[$eventName][] = $callback;
        return $this;
    }

    /**
     * Вызов события. Событие - это массив
     */
    public function emitEvent (string $eventName, array $data = []): BaseObject
    {
        $fullEventData = [
            'event'   => $eventName,
            'data'    => $data,
            'emitted' => $this
        ];

        if (isset($this->__eventHandlers[$eventName]))
        {
            foreach ($this->__eventHandlers[$eventName] as $key => $callback)
            {
                call_user_func($callback, $fullEventData);
            }
        }

        return $this;
    }

    /**
     * Удаляет прослушку события
     */
    public function off (string $eventName, ?callable $callback = NULL): BaseObject
    {
        if (isset($this->__eventHandlers[$eventName]))
        {
            if ($callback === NULL)
            {
                unset($this->__eventHandlers[$eventName]);
            } else
            {
                foreach ($this->__eventHandlers[$eventName] as $key => $callbackHandler)
                {
                    if ($callbackHandler == $callback)
                        unset($this->__eventHandlers[$eventName][$key]);
                }
            }
        }

        return $this;
    }

    /**
     * Защита объекта от добавления неопределнных полей
     */
    public function protect (): BaseObject
    {
        $this->__sealedObject = true;
        $this->emitEvent(self::EVENT_PROTECTION_ENABLED, []);
        return $this;
    }

    /**
     * Снятие защиты объекта от неопределенных полей
     */
    public function unprotect (): BaseObject
    {
        $this->__sealedObject = false;
        $this->emitEvent(self::EVENT_PROTECTION_DISABLED, []);
        return $this;
    }

    public function getObjectName (): string
    {
        return __CLASS__;
    }

    /**
     * @throws InvalidPropertyException
     */
    public function __set ($name, $value)
    {
        if ($this->__sealedObject)
            throw new InvalidPropertyException("Property " . $name . " can not be set in untEngine objects without definition.");

        $this->{$name} = $value;
    }

    /**
     * @throws InvalidPropertyException
     */
    public function __get ($name)
    {
        if ($this->__sealedObject)
            throw new InvalidPropertyException("Property " . $name . " can not be get in UntEngine objects without definition");

        return $this->{$name};
    }

    public function __toString (): string
    {
        return json_encode([
            __CLASS__ => $this
        ]);
    }
}