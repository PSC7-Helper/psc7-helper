<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Exception;

use psc7helper\App\Header\Header;

class ExceptionHandler {

    /**
     * level
     * @var mixed 
     */
    public static $level;

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
        if (!self::$level) {
            $errorReporting = 0;
        } if (self::$level === E_ALL) {
            $errorReporting = -1;
        }
        error_reporting($errorReporting);
        ini_set('error_reporting', self::$level);
        if (self::$level) {
            $displayErrors = 1;
        } else {
            $displayErrors = 0;
        }
        ini_set('display_errors', $displayErrors);
        set_error_handler('\psc7helper\App\Exception\Error::process');
        set_exception_handler('\psc7helper\App\Exception\Exception::handle');
        register_shutdown_function('\psc7helper\App\Exception\FatalError::process');
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
     * init
     * @param mixed $level
     * @return self
     */
    public static function init($level = false) {
        self::$level = $level;
        return self::getInstance();
    }

    /**
     * errnoToText
     * @param int $errno
     * @return string
     */
    public static function errnoToText($errno) {
        $errors = array(
            E_ERROR => 'Fatal error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse error',
            E_NOTICE => 'Notice',
            E_DEPRECATED => 'Deprecated',
            E_STRICT => 'Strict',
            E_CORE_ERROR => 'Fatal error',
            E_CORE_WARNING => 'Warning',
            E_COMPILE_ERROR => 'Fatal error',
            E_COMPILE_WARNING => 'Warning',
            E_USER_ERROR => 'Fatal error',
            E_USER_WARNING => 'Warning',
            E_USER_NOTICE => 'Notice',
            E_USER_DEPRECATED => 'Deprecated',
            E_RECOVERABLE_ERROR => 'Fatal error'
        );
        $toString = 'Unknown error';
        foreach ($errors as $key => $value) {
            if ($key === $errno) {
                $toString = $value;
            }
        }
        return $toString;
    }

    /**
     * checkExit
     * @param int $errno
     * @return boolean
     */
    public static function checkExit($errno) {
        $states = array(
            E_ERROR,
            E_PARSE,
            E_STRICT,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_USER_ERROR,
            E_RECOVERABLE_ERROR
        );
        if (in_array($errno, $states)) {
            Header::send(500);
            print 'An error has occurred. Please try again later. Thank you.';
            exit(1);
        }
        return false;
    }

    /**
     * isFileReachedMaxsize
     * @param string $file
     * @return int
     */
    public static function isFileReachedMaxsize($file) {
        if (!file_exists($file)) {
            return false;
        }
        $size = filesize($file);
        print $size;
        if ($size > 10000) {
            return true;
        }
        return false;
    }

}
