<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 *
 * PSC7-Helper
 * Copyright (c) 2018 Michael Rusch, Florian Wehrhausen, Waldemar Fraer
 *
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 *
 * @author Michael Rusch <michael@rusch.sh>
 * @author Florian Wehrhausen <fw-98@web.de>
 * @author Waldemar Fraer <waldemar@lumizil.de>
 */

define('DS', '/');
define('ROOT_PATH', __DIR__);
define('CONTROLLERS_PATH', ROOT_PATH . DS . 'controllers');
define('MODULES_PATH', ROOT_PATH . DS . 'modules');
define('THEMES_PATH', ROOT_PATH . DS . 'themes');
define('VAR_PATH', ROOT_PATH . DS . 'var');
define('VENDOR_PATH', ROOT_PATH . DS . 'vendor');

require ROOT_PATH . DS . 'vendor' . DS . 'autoload.php';
require ROOT_PATH . DS . 'config' . DS . 'config.php';

use psc7helper\App\Exception\ExceptionHandler;
use psc7helper\App\Http\Request;
use psc7helper\App\Bootstrap;
use psc7helper\App\Session\Session;
use psc7helper\App\Header\Header;

ExceptionHandler::init(E_ALL);

$requests = Request::getArguments();
$helper = new Bootstrap($requests);

if (Session::get('userid')) {
    phpinfo(INFO_ALL);
} else {
    Header::send(404);
    die();
}
