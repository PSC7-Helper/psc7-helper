<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Config;

use psc7helper\App\Common\Escape;

class Config
{
    /**
     * instance.
     *
     * @var object
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
     * init.
     *
     * @return self
     */
    public static function init()
    {
        return self::getInstance();
    }

    /**
     * set.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public static function set($key, $value)
    {
        $ekey = Escape::key($key);
        $evalue = Escape::value($value);
        if (! isset($GLOBALS['CONFIG'][$ekey])) {
            $GLOBALS['CONFIG'][$ekey] = $evalue;
        }

        return true;
    }

    /**
     * get.
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function get($key)
    {
        $ekey = Escape::key($key);
        if (isset($GLOBALS['CONFIG']) && array_key_exists($ekey, $GLOBALS['CONFIG'])) {
            return $GLOBALS['CONFIG'][$ekey];
        }

        return false;
    }

    /**
     * update.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public static function update($key, $value)
    {
        $ekey = Escape::key($key);
        $evalue = Escape::value($value);
        if (isset($GLOBALS['CONFIG'][$ekey])) {
            $GLOBALS['CONFIG'][$ekey] = $evalue;
        }

        return true;
    }

    /**
     * remove.
     *
     * @param string $key
     *
     * @return bool
     */
    public static function remove($key)
    {
        $ekey = Escape::key($key);
        if (isset($GLOBALS['CONFIG'][$ekey])) {
            unset($GLOBALS['CONFIG'][$ekey]);
        }

        return true;
    }
}
