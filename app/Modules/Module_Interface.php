<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Modules;

interface Module_Interface {

    /**
     * __construct
     * @param string $templatePath
     */
    public function __construct($templatePath);

    /**
     * run
     * @return boolean
     */
    public function run();

}
