<?php

use unt\objects\DataBase;
use unt\objects\HeadView;
use unt\objects\View;

UntEngine::get()->getConfig()->showErrors();
$db = DataBase::get();

try {
    $head = new HeadView(__DIR__ . '/views/head.html');
    $main = new View(__DIR__ . '/views/main.html');

    $head->setTitle('yunNet.')->setLang('ru')->show($main);
} catch (\unt\exceptions\FileNotFoundException $e) {
}
?>