<?php

use unt\lang\Language;
use unt\objects\Context;
use unt\objects\HeadView;
use unt\objects\View;

try {
    $head = (new HeadView(__DIR__ . '/views/head.html'))->setLang(Language::get()->id)->setTitle('yunNet.');
    try {
        $context = Context::get();

        $view = new View(__DIR__ . '/views/main.html');
    } catch (\unt\exceptions\IncorrectSessionException $e) {
        $view = new View(__DIR__ . '/views/auth.html');
    }

    $head->show($view);
} catch (\unt\exceptions\FileNotFoundException $e) {
    echo '<b>Failed to load the platform! Sorry...<b>';
}

?>