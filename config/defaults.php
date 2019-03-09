<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */
$swConfig = false;
if (file_exists('../config.php')) {
    $swConfig = require_once '../config.php';
}

if ($swConfig) {
    $dbPort = $swConfig['db']['port'];
    $dbHost = $swConfig['db']['host'];
    $dbUser = $swConfig['db']['username'];
    $dbPass = $swConfig['db']['password'];
    $dbName = $swConfig['db']['dbname'];
} else {
    $dbPort = '3306';
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = 'local';
    $dbName = 'shopware';
}

$GLOBALS['CONFIG'] = [
    //Database
    'dbPort'   => $dbPort,
    'dbHost'   => $dbHost,
    'dbUser'   => $dbUser,
    'dbPass'   => $dbPass,
    'dbName'   => $dbName,
    'dbPrefix' => '',

    //Frontend
    'theme'         => 'default',
    'name'          => 'PSC7-Helper',
    'copyright'     => 'Michael Rusch, Florian Wehrhausen, Waldemar Fraer',
    'copyrightYear' => '2018',

    //Date
    'dateTimezone' => 'Europe/Berlin',
    'dateFormat'   => 'd.m.Y',
    'timeFormat'   => 'H:i',

    //User
    'userDefaultLang' => 'de',

    //Session
    'sessionName'           => '_helperID',
    'sessionUseCookies'     => 1,
    'sessionUseOnlyCookies' => 1,
    'sessionCookieHttponly' => 1,
    'sessionCacheLimiter'   => 'nocache',
    'sessionUseTransSid'    => 0,
    'sessionRegenerate'     => 1,
];
