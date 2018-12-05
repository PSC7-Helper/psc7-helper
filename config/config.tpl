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



/**
 * PHP_PATH
 * This setting is important for the correct execution of the connector commands.
 * Please ask your hosting provider to get the correct path to php or try the follow command on your command line:
 * find /usr/bin -name php* -print
 * Try the following combinations:
 * php
 * /user/bin/php
 * /usr/bin/env php
 * /usr/bin/php70
 * /usr/bin/php7.0
 */
define('PHP_PATH', 'php');


/**
 * Theme
 * The place for all themes is /themes/[dir]. you can override the default theme
 * here by specifying the folder name of your theme.
 */
$GLOBALS['CONFIG']['theme'] = 'default';
