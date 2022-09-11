<?php

require_once __DIR__ . '/../bin/init.php';

$unt = UntEngine::get();
try {
    $unt->run();
} catch (\unt\exceptions\InvalidConfigException $e) {
    echo '<b>Failed to init platform. Sorry...</b>';
}

?>