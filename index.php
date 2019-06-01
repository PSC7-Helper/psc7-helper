<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * PSC7-Helper
 * Copyright (c) 2018 Michael Rusch, Florian Wehrhausen, Waldemar Fraer
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @author Michael Rusch <michael@rusch.sh>
 * @author Florian Wehrhausen <fw-98@web.de>
 * @author Waldemar Fraer <waldemar@lumizil.de>
 */
if (PHP_VERSION_ID < 50604) {
    header('Content-type: text/html; charset=utf-8', true, 503);
    echo 'Your server is running PHP version ' . PHP_VERSION . ' but the Helper requires at least PHP 5.6.4';

    return;
}

if (is_file('../files/update/update.json') || is_dir('update-assets')) {
    header('Content-type: text/html; charset=utf-8', true, 503);
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 1200');
    if (file_exists(__DIR__ . '/../maintenance.html')) {
        echo file_get_contents(__DIR__ . '/../maintenance.html');
    } else {
        echo file_get_contents(__DIR__ . '/../recovery/update/maintenance.html');
    }

    return;
}

if (is_dir('../recovery/install') && ! is_file('../recovery/install/data/install.lock')) {
    if (PHP_SAPI === 'cli') {
        echo 'Please run the Shopware installer by executing \'php recovery/install/index.php\'.' . PHP_EOL;
    } else {
        echo 'Shopware 5 must be configured before use. Please run the <a href="recovery/install/?language=en">installer</a>.';
    }
    exit;
}

define('DS', '/');
define('ROOT_PATH', __DIR__);
define('CONTROLLERS_PATH', ROOT_PATH . DS . 'controllers');
define('MODULES_PATH', ROOT_PATH . DS . 'modules');
define('THEMES_PATH', ROOT_PATH . DS . 'themes');

require ROOT_PATH . DS . 'vendor' . DS . 'autoload.php';
require ROOT_PATH . DS . 'config' . DS . 'config.php';

use psc7helper\App\Bootstrap;
use psc7helper\App\Exception\ExceptionHandler;
use psc7helper\App\Http\Request;

ExceptionHandler::init(E_ALL);

$requests = Request::getArguments();
$helper = new Bootstrap($requests);
$helper->run();
