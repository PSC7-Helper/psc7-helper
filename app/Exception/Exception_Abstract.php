<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Exception;

use psc7helper\App\Exception\Exception_Interface;
use psc7helper\App\Exception\ExceptionHandler;

class Exception_Abstract implements Exception_Interface {

    /**
     * file
     * @var string
     */
    protected static $file = 'exception.log';

    /**
     * dir
     * @var string
     */
    protected static $dir = ROOT_PATH . DS . 'var' . DS . 'log';

    /**
     * __construct
     */
    public function __construct() {
        
    }

    /**
     * handle
     * @param object $ex
     * @param string $catch
     * @return boolean
     */
    public static function handle($ex, $catch = '') {
        $errnoToString = ExceptionHandler::errnoToText($ex->getCode());
        if (!is_dir(self::$dir)) {
            mkdir(self::$dir);
        }
        $fh = fopen(self::$dir . DS . self::$file, "a+");
        $input = '[' . date('c') . '] ';
        if ($catch != '') {
            $input.= '[' . $catch . '] ';
        }
        $input.= $errnoToString . ': ' . $ex->getMessage() . ' in ' . $ex->getFile() . ' on line ' . $ex->getLine();
        $input.= "\r\n";
        fwrite($fh, $input);
        fclose($fh);
        if ($errnoToString == 'Unknown error') {
            ExceptionHandler::checkExit(E_ERROR);
        } else {
            ExceptionHandler::checkExit($ex->getCode());
        }
        return true;
    }

}
