<?php

namespace unt\objects;

class Letter extends BaseObject
{
    const EVENT_BEFORE_SEND = 'events.mail.send.before';
    const EVENT_AFTER_SEND  = 'events.mail.send.after';

    ////////////////////////////////////////////
    private string $theme;

    private ?View $body;

    public array $emails = [];

    public function __construct(string $theme = '', ?View $body = NULL)
    {
        parent::__construct();
        $this->protect();

        $this->theme = $theme;
        $this->body = $body;
    }

    public function to (string $email): Letter
    {
        if (!in_array($email, $this->emails))
            $this->emails[] = $email;

        return $this;
    }

    public function send (): bool
    {
        $this->emitEvent(self::EVENT_BEFORE_SEND, [
            'email' => $this->emails,
            'theme' => $this->getTheme(),
            'view'  => $this->getBody()
        ]);

        $success = false;
        foreach ($this->emails as $email) {
            $result = mail($email, $this->getTheme(), $this->getBody()->render());
            if ($result)
                $success = true;
        }

        $this->emitEvent(self::EVENT_AFTER_SEND, [
            'email' => $this->emails,
            'success' => $success
        ]);

        return $success;
    }

    public function getTheme (): string
    {
        return $this->theme;
    }

    public function getBody (): View
    {
        return $this->body;
    }

    /////////////////////////////////
    public static function create (string $theme = '', ?View $body = NULL): Letter
    {
        return new self($theme, $body);
    }
}