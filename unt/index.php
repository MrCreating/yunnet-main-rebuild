<?php

use unt\objects\Context;
use unt\objects\HeadView;
use unt\objects\View;

$domain = UntEngine::get()->getConfig()->getProjectDomain();

$head = (new HeadView())->setTitle('yunNet.')
        ->addScript('http://dev.'.$domain.'/js/design.js')
        ->addScript('http://dev.'.$domain.'/js/unt.js')
        ->addStyleSheet('http://dev.'.$domain.'/css/design.css')
        ->addStyleSheet('http://dev.'.$domain.'/css/default_theme.css')
        ->addStyleSheet('https://fonts.googleapis.com/icon?family=Material+Icons');

try {
    try {
        $context = Context::get();

        $view = new View(__DIR__ . '/views/main.php');
    } catch (Exception $e) {
        $view = new View(__DIR__ . '/views/auth.php');
    }

    $head->addView($view);
} catch (\unt\exceptions\FileNotFoundException $e) {
    echo '<b>Failed to load the platform! Sorry...<b>';
}

$head->build();
?>