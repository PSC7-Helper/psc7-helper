<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Http;

use psc7helper\App\Common\Escape;

class Request {

    /**
     * arguments
     * @var array
     */
    private static $arguments = array();

    /**
     * file
     * @var array 
     */
    private static $file;

    /**
     * instance
     * @var self
     */
    private static $instance;

    /**
     * __construct
     */
    private function __construct() {
        
    }

    /**
     * __clone
     */
    final private function __clone() {
        
    }

    /**
     * getInstance
     * @return self
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * getArguments
     * @return array
     */
    public static function getArguments() {
        $time = array('posttime' => time());
        $arguments = array_merge($time, $_GET, $_POST, $_REQUEST);
        foreach ($arguments as $key => $value) {
            self::$arguments[$key] = Escape::value($value);
        }
        return self::$arguments;
    }

    /**
     * getFiles
     * @return array
     */
    public static function getFile() {
        self::$files = $_FILES;
        return self::$files;
    }

}
