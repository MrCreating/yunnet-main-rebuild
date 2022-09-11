<?php

namespace unt\lang;

use unt\objects\BaseObject;

class Language extends BaseObject
{
    // ВСЕ ЗНАЧЕНИЯ ДЛЯ ВСЕХ ЯЗЫКОВ
    public string $id;

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