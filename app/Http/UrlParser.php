<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Http;

class UrlParser
{
    /**
     * instance.
     *
     * @var self
     */
    private static $instance;

    /**
     * __construct.
     *
     * @param mixed $level
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
     * get.
     *
     * @return string
     */
    public static function get(string $key)
    {
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parse = parse_url($url);
        if (array_key_exists($key, $parse)) {
            return $parse[$key];
        }

        return '';
    }

    /**
     * getRootUrl.
     *
     * @return string
     */
    public static function getRootUrl()
    {
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parse = parse_url($url);
        $templateUrl = '';
        if (array_key_exists('host', $parse)) {
            $templateUrl .= parse_url($url, PHP_URL_HOST);
        }
        if (array_key_exists('port', $parse)) {
            $templateUrl .= ':' . parse_url($url, PHP_URL_PORT);
        }
        if (array_key_exists('path', $parse)) {
            $templateUrl .= str_replace(['index.php', 'index.php/', '//'], '', parse_url($url, PHP_URL_PATH));
        }
        if ('/' == substr($templateUrl, -1)) {
            $templateUrl = substr($templateUrl, 0, -1);
        }

        return $templateUrl;
    }

    /**
     * getBaseUrl.
     *
     * @return string
     */
    public static function getBaseUrl()
    {
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parse = parse_url($url);
        $templateUrl = '';
        if (array_key_exists('host', $parse)) {
            $templateUrl .= parse_url($url, PHP_URL_HOST);
        }
        if (array_key_exists('port', $parse)) {
            $templateUrl .= ':' . parse_url($url, PHP_URL_PORT);
        }
        if (array_key_exists('path', $parse)) {
            $templateUrl .= str_replace(['index.php', 'index.php/', '//'], '', parse_url($url, PHP_URL_PATH));
        }
        if ('/' == substr($templateUrl, -1)) {
            $templateUrl = substr($templateUrl, 0, -1);
        }

        return $templateUrl;
    }

    /**
     * getCanonicalUrl.
     *
     * @return string
     */
    public static function getCanonicalUrl()
    {
        $url = $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parse = parse_url($url);
        $templateUrl = '';
        if (array_key_exists('host', $parse)) {
            $templateUrl .= parse_url($url, PHP_URL_HOST);
        }
        if (array_key_exists('port', $parse)) {
            $templateUrl .= ':' . parse_url($url, PHP_URL_PORT);
        }
        if (array_key_exists('path', $parse)) {
            $templateUrl .= parse_url($url, PHP_URL_PATH);
        }
        if (array_key_exists('query', $parse)) {
            $templateUrl .= '?' . parse_url($url, PHP_URL_QUERY);
        }
        if ('/' == substr($templateUrl, -1)) {
            $templateUrl = substr($templateUrl, 0, -1);
        }

        return $templateUrl;
    }
}
