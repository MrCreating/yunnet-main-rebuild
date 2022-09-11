<?php
return [
    'db'     => require_once __DIR__ . '/db.php',
    'errors' => require_once __DIR__ . '/errors.php',

    'domain'        => 'yunnet.ru',
    'dev_domain'    => 'localhost',

    // domains list
    'domains' => ['api', 'dev', 'd-1', 'themes'],

    // main index router
    'router' => function ($domain) {
        if ($domain == '') {
            die(require_once __DIR__ . '/../../unt/index.php');
        }
        if ($domain == 'api') {
            die(require_once __DIR__ . '/../../api/index.php');
        }
        if ($domain == 'dev') {
            die(require_once __DIR__ . '/../../dev/index.php');
        }
        if ($domain == 'd-1') {
            die(require_once __DIR__ . '/../../doc/index.php');
        }
        if ($domain == 'uploads') {
            die(require_once __DIR__ . '/../../uploads/index.php');
        }
        if ($domain == 'themes') {
            die(require_once __DIR__ . '/../../themes/index.php');
        }

        $defaultRedirectUrl = UntEngine::get()->getConfig()->getProjectDomain(!((int) getenv('UNT_PRODUCTION')));
        header("Location: http://" . $defaultRedirectUrl . '/');
    }
];
?>