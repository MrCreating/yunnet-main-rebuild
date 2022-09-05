<?php

require_once __DIR__ . '/../bin/init.php';

use unt\objects\Config;

$unt = UntEngine::get();
$config = new Config();

$config->on(Config::EVENT_SHOWN_ERRORS, function ($event) {
    echo "Errors now showm!" . "<br>";
});
$config->on(\unt\objects\Config::EVENT_BEFORE_APPLY, function ($event) {
    echo "Action before apply! At this point you can create untEngine objects only." . '<br>';
});
$config->on(\unt\objects\Config::EVENT_AFTER_APPLY, function ($event) {
    echo "Action after apply! At this point you can work with database!" . '<br>';
});

$config->showErrors();
$unt->run($config);

?>