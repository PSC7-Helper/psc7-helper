<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Systeminfo_time;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;
use psc7helper\App\Date\Date;

class Module extends Module_Abstract implements Module_Interface {

    /**
     * run
     * @return string
     */
    public function run() {
        $this->setPlaceholder('cardtitle', __('time_cardtitle'), false);
        $this->setPlaceholder('time', Date::getDate('d.m.Y H:i'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }

}
