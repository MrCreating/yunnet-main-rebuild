<?php

/**
 * Обработчики ошибок. Доступа в основной контекст untEngine тут нет.
 */

return [
    E_ALL => function ($error) {
        var_dump($error);
    },
    E_NOTICE => function ($error) {
        var_dump($error);
    },
    E_STRICT => function ($error) {
        var_dump($error);
    },
    E_DEPRECATED => function ($error) {
        var_dump($error);
    },
    'fatal' => function ($err) {
        return $err;
    }
];
?>
