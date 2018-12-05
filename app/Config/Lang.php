<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Config;

use psc7helper\App\Common\Escape;

class Lang {

    /**
     * instance
     * @var object
     */
    private static $instance;

    /**
     * __construct
     */
    private function __construct() {
        $this->setLang();
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
        if (self::$instance === NULL) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * init
     * @return self
     */
    public static function init() {
        return self::getInstance();
    }

    /**
     * setLang
     * @return $this
     */
    private function setLang() {
        $lang = Config::get('userDefaultLang');
        return $this;
    }

    /**
     * set
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public static function set($key, $value) {
        $ekey = Escape::key($key);
        $evalue = Escape::value($value);
        if (!isset($GLOBALS['LANG'][$ekey])) {
            $GLOBALS['LANG'][$ekey] = $evalue;
        }
        return true;
    }

    /**
     * setMulti
     * Set multiple lang sets be given a key/value array
     * @param array $langSet
     * @return boolean
     */
    public static function setMulti($langSet) {
        foreach ($langSet as $key => $value) {
            self::set(Escape::key($key), Escape::value($value));
        }
        return true;
    }

    /**
     * get
     * @param string $key
     * @return mixed
     */
    public static function get($key) {
        $ekey = Escape::key($key);
        if (isset($GLOBALS['LANG']) && array_key_exists($ekey, $GLOBALS['LANG'])) {
            return $GLOBALS['LANG'][$ekey];
        }
        return false;
    }

    /**
     * update
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public static function update($key, $value) {
        $ekey = Escape::key($key);
        $evalue = Escape::value($value);
        if (isset($GLOBALS['LANG'][$ekey])) {
            $GLOBALS['LANG'][$ekey] = $evalue;
        }
        return true;
    }

    /**
     * remove
     * @param string $key
     * @return boolean
     */
    public static function remove($key) {
        $ekey = Escape::key($key);
        if (isset($GLOBALS['LANG'][$ekey])) {
            unset($GLOBALS['LANG'][$ekey]);
        }
        return true;
    }

}
