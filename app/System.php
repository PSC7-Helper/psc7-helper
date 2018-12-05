<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App;

use psc7helper\App\Date\Date;
use psc7helper\App\Config\Config;
use psc7helper\App\Config\Lang;
use psc7helper\App\Session\Session;
use psc7helper\App\User\User;
use psc7helper\App\Header\Header;

final class System {

    /**
     * file
     * @var string
     */
    private static $file = 'system.log';

    /**
     * dir
     * @var string
     */
    private static $dir = ROOT_PATH . DS . 'var' . DS . 'log';

    /**
     * instance
     * @var self
     */
    protected static $instance;

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
     * initialize
     * @return boolean
     */
    public static function initialize() {
        Date::init();
        Config::init();
        Lang::init();
        Session::init();
        User::init();
        return true;
    }

    /**
     * log
     * @param string $message
     * @param bool $exit
     * @return boolean
     */
    public static function log($message, $exit = false) {
        if (!is_dir(self::$dir)) {
            mkdir(self::$dir);
        }
        $fh = fopen(self::$dir . DS . self::$file, "a+");
        $input = '[' . date('c') . '] ';
        $input.= $message;
        $input.= "\r\n";
        fwrite($fh, $input);
        fclose($fh);
        if ($exit) {
            Header::send(500);
            print 'An error has occurred. Please try again later. Thank you.';
            exit(1);
        }
        return true;
    }

}
