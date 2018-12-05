<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Controller\Dashboard;

use psc7helper\App\Controllers\Controller_Abstract;
use psc7helper\App\Controllers\Controller_Interface;

class Controller extends Controller_Abstract implements Controller_Interface {

    /**
     * index
     * @return string
     */
    public function index() {
        $this->setTemplate('index');
        $page = $this->renderPage('index');
        return $page;
    }

    /**
     * systeminfo
     * @return string
     */
    public function systeminfo() {
        $this->setTemplate('systeminfo');
        $page = $this->renderPage('index');
        return $page;
    }

    /**
     * phpinfo
     * @return string
     */
    public function phpinfo() {
        $this->setTemplate('phpinfo');
        $page = $this->renderPage('index');
        return $page;
    }

    /**
     * help
     * @return string
     */
    public function help() {
        $this->setTemplate('help');
        $page = $this->renderPage('index');
        return $page;
    }

}
