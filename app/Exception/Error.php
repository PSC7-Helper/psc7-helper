<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Exception;

use psc7helper\App\Exception\ExceptionHandler;

class Error {

    /**
     * file
     * @var string
     */
    private static $file = 'exception.log';

    /**
     * dir
     * @var string
     */
    private static $dir = ROOT_PATH . DS . 'var' . DS . 'log';

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
     * process
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return boolean
     */
    public static function process($errno, $errstr, $errfile, $errline) {
        if (strpos($errstr, 'ob_end_flush') !== false) {
            return true;
        }
        $errnoToString = ExceptionHandler::errnoToText($errno);
        if (!is_dir(self::$dir)) {
            mkdir(self::$dir);
        }
        $fh = fopen(self::$dir . DS . self::$file, "a+");
        date_default_timezone_set('Europe/Berlin');
        $input = '[' . date('c') . '] ';
        $input.= $errnoToString . ': ' . $errstr . ' in ' . $errfile . ' on line ' . $errline;
        $input.= "\r\n";
        fwrite($fh, $input);
        fclose($fh);
        if ($errnoToString == 'Unknown error') {
            ExceptionHandler::checkExit(E_ERROR);
        } else {
            ExceptionHandler::checkExit($errno);
        }
        return true;
    }

}
