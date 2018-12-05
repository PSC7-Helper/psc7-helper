<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App;

use psc7helper\App\Bootstrap;
use psc7helper\App\Bootstrap_Interface;
use psc7helper\App\System;
use psc7helper\App\Header\Header;

class Ajax extends Bootstrap implements Bootstrap_Interface {

    /**
     * CONTROLLERNAMESPACE
     */
    const CONTROLLERNAMESPACE = '\\psc7helper\\Controller\\';

    /**
     * MODULENAMESPACE
     */
    const MODULENAMESPACE = '\\psc7helper\\Module\\';

    /**
     * type
     * @var string 
     */
    protected $type = false;

    /**
     * name
     * @var string 
     */
    protected $name = false;

    /**
     * file
     * @var string
     */
    protected $file;

    /**
     * classNamespace
     * @var string
     */
    protected $classNamespace;

    /**
     * ajaxAction
     * @var string 
     */
    protected $ajaxAction = false;

    /**
     * param
     * @var string 
     */
    protected $param = false;

    /**
     * __construct
     * @param array $requests
     */
    public function __construct($requests) {
        parent::__construct($requests);
        $this->requests = $requests;
        $this->setRequestdata()
             ->setNamespace();
    }

    /**
     * setRequestdata
     * @return $this
     */
    private function setRequestdata() {
        $requests = $this->requests;
        if (array_key_exists('type', $requests) && !empty($requests['type'])) {
            switch ($requests['type']) {
                case 'c':
                    $this->type = 'controller';
                    break;
                case 'm':
                    $this->type = 'module';
                    break;
            }
        }
        if (array_key_exists('n', $requests) && !empty($requests['n'])) {
            $this->name = ucfirst($requests['n']);
        }
        if (array_key_exists('a', $requests) && !empty($requests['a'])) {
            $this->ajaxAction = $requests['a'];
        }
        if (array_key_exists('p', $requests) && !empty($requests['p'])) {
            $this->param = $requests['p'];
        }
        return $this;
    }

    /**
     * setNamespace
     * @return $this
     */
    public function setNamespace() {
        $file = false;
        $namespace = false;
        $type = $this->type;
        $name = $this->name;
        if ($type == 'controller') {
            $file = CONTROLLERS_PATH . DS . ucfirst($name) . DS . 'Ajax.php';
            $namespace = self::CONTROLLERNAMESPACE . ucfirst($name) . '\\Ajax';
        }
        if ($type == 'module') {
            $file = MODULES_PATH . DS . ucfirst($name) . DS . 'Ajax.php';
            $namespace = self::MODULENAMESPACE . ucfirst($name) . '\\Ajax';
        }
        $this->file = $file;
        $this->classNamespace = $namespace;
        return $this;
    }

    /**
     * output
     * @return object
     */
    public function output() {
        if (!$this->login) {
            Header::send(403);
            System::log('output: not logged in ' . __FILE__ . ' on line ' . __LINE__, false);
            exit('Login error');
        }
        $file = $this->file;
        if (!file_exists($file)) {
            Header::send(404);
            System::log('output: class not found in ' . __FILE__ . ' on line ' . __LINE__, false);
            exit('Request not found');
        }
        $path = str_replace('/Ajax.php', '', $file);
        $action = $this->ajaxAction;
        $param = $this->param;
        $class = $this->classNamespace;
        $reflectionClass = new \ReflectionClass($class);
        $args = array($path, $action, $param);
        $instance = $reflectionClass->newInstanceArgs($args);
        
        return $instance;
    }

}
