<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Session;

use psc7helper\App\Config\Config;
use psc7helper\App\User\User;
use psc7helper\App\Session\Model;
use psc7helper\App\Common\Escape;

class Session {

    /**
     * instance
     * @var object
     */
    protected static $instance;

    /**
     * __construct
     */
    private function __construct() {
        ini_set('session.name', Config::get('sessionName'));
        ini_set('session.use_cookies', Config::get('sessionUseCookies'));
        ini_set('session.use_only_cookies', Config::get('sessionUseOnlyCookies'));
        ini_set('session.cookie_httponly', Config::get('sessionCookieHttponly'));
        ini_set('session.cache_limiter', Config::get('sessionCacheLimiter'));
        ini_set('session.use_trans_sid', Config::get('sessionUseTransSid'));
        $this->start();
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
     * @return self
     */
    public static function init() {
        return self::getInstance();
    }

    /**
     * start
     * @return $this
     */
    private function start() {
        if (!isset($_SESSION['init'])) {
            session_start();
            self::set('init', time());
        }
        return $this;
    }

    /**
     * login
     * @param int $userID
     * @return boolean
     */
    public static function login($userID) {
        self::set('userid', $userID);
        self::regenerate();
        return true;
    }

    /**
     * isStarted
     * @return boolean
     */
    public static function isStarted() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }
        return false;
    }

    /**
     * isInit
     * @return boolean
     */
    public static function isInit() {
        if (self::get('init') !== false) {
            return true;
        }
        return false;
    }

    /**
     * validateLogin
     * @return boolean
     */
    public static function validateLogin() {
        if (!self::isStarted() || !self::isInit()) {
            return false;
        }
        $userID = User::get('userid');
        if (!$userID || !is_int($userID)) {
            self::destroy();
            return false;
        }
        $userIDsess = self::get('userid');
        if (!$userIDsess || !is_int($userIDsess)) {
            self::destroy();
            return false;
        }
        $model = new Model();
        if (!$model->validateUserID($userID)) {
            self::destroy();
            return false;
        }
        return true;
    }

    /**
     * regenerate
     * @return boolean
     */
    public static function regenerate() {
        if (Config::get('sessionRegenerate')) {
            session_regenerate_id(true);
        }
        return true;
    }

    /**
     * destroy
     * @return boolean
     */
    public static function destroy() {
        $_SESSION = null;
        session_destroy();
        session_start();
        self::set('init', time());
        if (Config::get('sessionRegenerate')) {
            session_regenerate_id(true);
        }
        return true;
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
        if (!isset($_SESSION[$ekey])) {
            $_SESSION[$ekey] = $evalue;
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
        if (isset($_SESSION) && array_key_exists($ekey, $_SESSION)) {
            return $_SESSION[$ekey];
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
        if (isset($_SESSION[$ekey])) {
            $_SESSION[$ekey] = $evalue;
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
        if (isset($_SESSION[$ekey])) {
            unset($_SESSION[$ekey]);
        }
        return true;
    }

}
