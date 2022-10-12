<?php

namespace unt\lang;

use unt\objects\BaseObject;

class Language extends BaseObject
{
    // ВСЕ ЗНАЧЕНИЯ ДЛЯ ВСЕХ ЯЗЫКОВ
    public string $id;
    public string $email;
    public string $password;
    public string $login;
    public string $welcome;
    public string $login_invitation;
    public string $email_welcome;
    public string $email_code;

    ///////////////////////////////

    public static function get (): Language
    {
        $lang = [
            'ru' => RussianLanguage::class,
            'en' => EnglishLanguage::class
        ];

        return new $lang['ru'];
    }
}