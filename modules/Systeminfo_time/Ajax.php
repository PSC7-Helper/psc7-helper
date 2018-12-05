<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Systeminfo_time;

use psc7helper\App\Ajax\Ajax_Abstract;
use psc7helper\App\Ajax\Ajax_Interface;
use psc7helper\App\Date\Date;

class Ajax extends Ajax_Abstract implements Ajax_Interface {

    /**
     * datetime
     * @return string
     */
    public function datetime() {
        print Date::getDate('d.m.Y H:i');
    }

}
