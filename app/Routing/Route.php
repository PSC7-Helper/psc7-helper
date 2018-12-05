<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Routing;

use psc7helper\App\Common\Escape;

class Route {

    /**
     * CONTROLLERKEX
     */
    const CONTROLLERKEY = 'controller';

    /**
     * ACTIONKEY
     */
    const ACTIONKEY = 'action';

    /**
     * PARAMKEY
     */
    const PARAMKEY = 'param';

    /**
     * controller
     * @var string 
     */
    private $controller;

    /**
     * action
     * @var string 
     */
    private $action;

    /**
     * param
     * @var string
     */
    private $param;

    /**
     * requests
     * @var array
     */
    private $requests;

    /**
     * __construct
     */
    public function __construct($requests = array()) {
        $this->requests = $requests;
        $this->setController()
             ->setAction()
             ->setParam();
    }

    /**
     * setController
     * @return $this
     */
    private function setController() {
        $controller = $this->find(self::CONTROLLERKEY);
        if ($controller) {
            $this->controller = $controller;
        } else {
            $this->controller = 'dashboard';
        }
        return $this;
    }

    /**
     * setAction
     * @return $this
     */
    private function setAction() {
        $action = $this->find(self::ACTIONKEY);
        if ($action) {
            $this->action = $action;
        } else {
            $this->action = 'index';
        }
        return $this;
    }

    /**
     * setParms
     * @return $this
     */
    private function setParam() {
        $param = $this->find(self::PARAMKEY);
        if ($param) {
            $this->param = $param;
        } else {
            $this->param = '';
        }
        return $this;
    }

    /**
     * find
     * @param string $key
     * @return string
     */
    private function find($key) {
        $requests = $this->requests;
        if (array_key_exists($key, $requests)) {
            return Escape::route($requests[$key], 40);
        }
        return false;
    }

    /**
     * getController
     * @return string
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * getAction
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * getParms
     * @return array
     */
    public function getParam() {
        return $this->param;
    }

}
