<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Exception;

class FatalError
{
    /**
     * file.
     *
     * @var string
     */
    private static $file = 'exception.log';

    /**
     * dir.
     *
     * @var string
     */
    private static $dir = ROOT_PATH . DS . 'var' . DS . 'log';

    /**
     * instance.
     *
     * @var self
     */
    private static $instance;

    /**
     * __construct.
     */
    private function __construct()
    {
    }

    /**
     * __clone.
     */
    private function __clone()
    {
    }

    /**
     * getInstance.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * process.
     *
     * @return bool
     */
    public static function process()
    {
        $error = error_get_last();
        if (E_ERROR === $error['type']) {
            $file = self::$dir . DS . self::$file;
            $errnoToString = ExceptionHandler::errnoToText($error['type']);
            if (! is_dir(self::$dir)) {
                mkdir(self::$dir);
            }
            $input = '[' . date('c') . '] ';
            $input .= $errnoToString . ': ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line'];
            $input .= "\r\n";
            if (! ExceptionHandler::isFileReachedMaxsize($file)) {
                $fh = fopen($file, 'a+');
                fwrite($fh, $input);
                fclose($fh);
            }
            if ('Unknown error' == $errnoToString) {
                ExceptionHandler::checkExit(E_ERROR);
            } else {
                ExceptionHandler::checkExit($error['type']);
            }
        }

        return true;
    }
}
