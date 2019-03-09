<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Session;

use psc7helper\App\Common\Escape;
use psc7helper\App\Config\Config;
use psc7helper\App\User\User;

class Session
{
    /**
     * instance.
     *
     * @var object
     */
    protected static $instance;

    /**
     * __construct.
     */
    private function __construct()
    {
        ini_set('session.name', Config::get('sessionName'));
        ini_set('session.use_cookies', Config::get('sessionUseCookies'));
        ini_set('session.use_only_cookies', Config::get('sessionUseOnlyCookies'));
        ini_set('session.cookie_httponly', Config::get('sessionCookieHttponly'));
        ini_set('session.cache_limiter', Config::get('sessionCacheLimiter'));
        ini_set('session.use_trans_sid', Config::get('sessionUseTransSid'));
        $this->start();
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
     * start.
     *
     * @return $this
     */
    private function start()
    {
        if (! isset($_SESSION['init'])) {
            session_start();
            self::set('init', time());
        }

        return $this;
    }

    /**
     * login.
     *
     * @param int $userID
     *
     * @return bool
     */
    public static function login($userID)
    {
        self::set('userid', $userID);
        self::regenerate();

        return true;
    }

    /**
     * isStarted.
     *
     * @return bool
     */
    public static function isStarted()
    {
        if (PHP_SESSION_ACTIVE === session_status()) {
            return true;
        }

        return false;
    }

    /**
     * isInit.
     *
     * @return bool
     */
    public static function isInit()
    {
        if (false !== self::get('init')) {
            return true;
        }

        return false;
    }

    /**
     * validateLogin.
     *
     * @return bool
     */
    public static function validateLogin()
    {
        if (! self::isStarted() || ! self::isInit()) {
            return false;
        }
        $userID = User::get('userid');
        if (! $userID || ! is_int($userID)) {
            self::destroy();

            return false;
        }
        $userIDsess = self::get('userid');
        if (! $userIDsess || ! is_int($userIDsess)) {
            self::destroy();

            return false;
        }
        $model = new Model();
        if (! $model->validateUserID($userID)) {
            self::destroy();

            return false;
        }

        return true;
    }

    /**
     * regenerate.
     *
     * @return bool
     */
    public static function regenerate()
    {
        if (Config::get('sessionRegenerate')) {
            session_regenerate_id(true);
        }

        return true;
    }

    /**
     * destroy.
     *
     * @return bool
     */
    public static function destroy()
    {
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
        if (! isset($_SESSION[$ekey])) {
            $_SESSION[$ekey] = $evalue;
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
        if (isset($_SESSION) && array_key_exists($ekey, $_SESSION)) {
            return $_SESSION[$ekey];
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
        if (isset($_SESSION[$ekey])) {
            $_SESSION[$ekey] = $evalue;
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
        if (isset($_SESSION[$ekey])) {
            unset($_SESSION[$ekey]);
        }

        return true;
    }
}
