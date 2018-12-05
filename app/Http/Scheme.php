<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Http;

class Scheme {

    /**
     * instance
     * @var self
     */
    private static $instance;

    /**
     * __construct
     * @param mixed $level
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
     * get
     * @return string
     */
    public static function get() {
        if (isset($_SERVER['HTTPS']) && is_string($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            return 'https://';
        }
        return 'http://';
    }

}
