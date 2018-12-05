<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Controllers;

interface Controller_Interface {

    /**
     * __construct
     * @param array $requests
     * @param string $templatePath
     * @param string $controllerName
     * @param string $action
     * @param string $param
     * @param bool $login
     * @param string $theme
     */
    public function __construct($requests, $templatePath, $controllerName, $action, $param, $login, $theme);

    /**
     * isActionExists
     * @param string $action
     * @return boolean
     */
    public function isActionExists($action);

    /**
     * setPlaceholder
     * @param string $key
     * @param string $value
     * @param bool $html
     * @return $this
     */
    public function setPlaceholder($key, $value, $html = false);

    /**
     * setIndexTemplate
     * @param string $name
     * @return $this
     */
    public function setIndexTemplate($name);

    /**
     * setTemplate
     * @param string $name
     * @return $this
     */
    public function setTemplate($name);

    /**
     * renderPage
     * @param string $index
     * @return boolean
     */
    public function renderPage($index = 'index');

    /**
     * error
     * @param int $code
     * @return boolean
     */
    public function error($code);

    /**
     * index
     */
    public function index();

}
