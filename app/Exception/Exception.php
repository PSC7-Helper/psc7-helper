<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Exception;

use psc7helper\App\Exception\Exception_Abstract;

class Exception extends Exception_Abstract {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->file = 'exception.log';
    }

}