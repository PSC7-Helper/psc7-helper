<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Common;

class Escape {

    /**
     * instance
     * @var self
     */
    private static $instance;

    /**
     * __construct
     * @param string $mode
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
     * key
     * @param string $key
     * @return string
     */
    public static function key($key) {
        return (string) htmlspecialchars(trim($key));
    }

    /**
     * value
     * @param $value
     * @param int $length
     * @param bool $html
     * @return mixed
     */
    public static function value($value, $length = 255, $html = false) {
        if (is_bool($value)) {
            return boolval($value);
        }
        if (is_int($value)) {
            return intval(substr($value, 0, $length));
        }
        if (is_float($value)) {
            return floatval(substr($value, 0, $length));
        }
        if (strpos($value, '?') !== false && strpos($value, '&amp;') !== false && strpos($value, '=') !== false) {
            return urldecode($value);
        }
        if (is_string($value)) {
            if ($html) {
                return strval(trim(strip_tags($value, self::allowableTags())));
            } else {
                if ($length > 255) {
                    return strval(substr(trim(strip_tags($value, self::allowableTags()))), 0, $length);
                }
                return strval(trim(strip_tags($value, self::allowableTags())));
            }
        }
    }

    /**
     * html
     * @param string $value
     * @return string
     */
    public function html($value) {
        return (string) strip_tags($value, self::allowableTags());
    }

    /**
     * allowableTags
     * @return string
     */
    public static function allowableTags() {
        $tags = array(
            '<a>', '<abbr>', '<adress>', '<area>', '<article>', '<aside>',
            '<audio>', '<b>', '<blockquote>', '<br>', '<button>', '<canvas>',
            '<caption>', '<code>', '<data>', '<datalist>', '<details>', '<dialog>',
            '<div>', '<dl>', '<dt>', '<em>', '<embed>', '<fieldset>', '<figcaption>',
            '<figure>', '<footer>', '<form>', '<h1>', '<h2>', '<h3>', '<h4>', '<h5>',
            '<h6>', '<header>', '<hr>', '<i>', '<iframe>', '<img>', '<input>',
            '<label>', '<legend>', '<li>', '<main>', '<map>', '<mark>', '<meta>',
            '<meter>', '<nav>', '<noscript>', '<object>', '<ol>', '<optgroup>',
            '<option>', '<output>', '<p>', '<param>', '<picture>', '<pre>',
            '<progress>', '<q>', '<samp>', '<script>', '<section>', '<select>',
            '<small>', '<source>', '<span>', '<strong>', '<sub>', '<summary>',
            '<sup>', '<svg>', '<table>', '<tbody>', '<td>', '<template>', '<textarea>',
            '<tfoot>', '<th>', '<thead>', '<time>', '<tr>', '<track>', '<u>', '<ul>',
            '<var>', '<video>', '<wbr>', '(' , ')'
        );
        return (string) implode('.', $tags);
    }

    /**
     * input
     * @param string $key
     * @param string $type (GET, POST, COOKIE, SERVER, ENV)
     * @param string $filter (email, encoded, float, int, special_chars, full_special_chars, string, url)
     * @return mixed
     */
    public static function input($key, $type = 'GET', $filter = 'string') {
        switch (strtoupper($type)) {
            case 'GET':
                $type = INPUT_GET;
                break;
            case 'POST':
                $type = INPUT_POST;
                break;
            case 'COOKIE':
                $type = INPUT_COOKIE;
                break;
            case 'SERVER':
                $type = INPUT_SERVER;
                break;
            case 'ENV':
                $type = INPUT_ENV;
                break;
            default:
                $type = INPUT_GET;
                break;
        }
        switch (strtolower($filter)) {
            case 'ip':
                $filter = FILTER_VALIDATE_IP;
                break;
            case 'email':
                $filter = FILTER_SANITIZE_EMAIL;
                break;
            case 'encoded':
                $filter = FILTER_SANITIZE_ENCODED;
                break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                break;
            case 'special_chars':
            case 'specialchars':
            case 'sc':
                $filter = FILTER_SANITIZE_SPECIAL_CHARS;
                break;
            case 'full_special_chars':
            case 'fullspecialchars':
            case 'fsc':
                $filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS;
                break;
            case 'string':
                $filter = FILTER_SANITIZE_STRING;
                break;
            case 'url':
                $filter = FILTER_SANITIZE_URL;
                break;
            default:
                $filter = FILTER_DEFAULT;
                break;
        }
        return filter_input($type, self::key($key), $filter);
    }

    /**
     * route
     * @param string $value
     * @param int $lenght
     * @return string
     */
    public static function route($value, $lenght = 0) {
        $buffer = $value;
        $buffer = trim($buffer);
        if ($lenght != 0) {
            $buffer = substr($buffer, 0, $lenght);
        }
        $buffer = preg_replace('/[^a-zA-Z0-9-]/', '', $buffer);
        return $buffer;
    }

}
