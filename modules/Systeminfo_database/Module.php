<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Systeminfo_database;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;
use psc7helper\App\Database\Database;

class Module extends Module_Abstract implements Module_Interface {

    /**
     * sql
     * @return array
     */
    private function sql() {
        $sql = array();
        $database = new Database();
        $info = $database->getConnection();
        $sql['type'] = $info['ATTR_DRIVER_NAME'];
        $sql['version'] = $info['ATTR_SERVER_VERSION'];
        return $sql;
    }

    /**
     * run
     * @return string
     */
    public function run() {
        $this->setPlaceholder('cardtitle', __('database_cardtitle'), false);
        $sql = $this->sql();
        $this->setPlaceholder('version', $sql['version'], false);
        $this->setPlaceholder('type', $sql['type'], false);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }

}
