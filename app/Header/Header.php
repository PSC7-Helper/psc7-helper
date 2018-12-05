<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Header;

use psc7helper\App\Http\Protocol;

class Header {

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
     * send
     * @param mixed $code
     * @param string $redirect
     * @return boolean
     */
    public static function send($code, $redirect = '') {
        if (headers_sent()) {
            header_remove();
        }
        $header = new self;
        switch ($code) {
            case 'x-powered-by':
                header('X-Powered-By: PHP');
                break;
            case 'content-type':
                header('Content-Type: text/html; charset=utf-8');
                break;
            case 'cache':
                header('Cache-Control: private, max-age=86400');
                header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400).' GMT');
                break;
            case 'no-cache':
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Pragma: no-cache');
                break;
            case 'refresh':
                header('Refresh:0');
                break;
            case 100:
            case '100':
                header(Protocol::get() . ' 100 Continue');
                break;
            case 102:
            case '102':
                header(Protocol::get() . ' 102 Processing');
                break;
            case 200:
            case '200':
                header(Protocol::get() . ' 200 OK');
                break;
            case 204:
            case '204':
                header(Protocol::get() . ' 204 No Content');
                break;
            case 301:
            case '301':
                if ($redirect) {
                    header(Protocol::get() . ' 301 Moved Permanently');
                    header('Location: ' . $redirect);
                    exit(0);
                }
                break;
            case 302:
            case '302':
                if ($redirect != '') {
                    header(Protocol::get() . ' 302 Found (Moved Temporarily)');
                    header('Location: ' . $redirect);
                    exit(0);
                }
                break;
            case 401:
            case '401':
                header(Protocol::get() . ' 401 Unauthorized');
                break;
            case 403:
            case '403':
                header(Protocol::get() . ' 403 Forbidden');
                break;
            case 404:
            case '404':
                header(Protocol::get() . ' 404 Not Found');
                break;
            case 429:
            case '429':
                header(Protocol::get() . ' 429 Too Many Requests');
                break;
            case 500:
            case '500':
                header(Protocol::get() . ' 500 Internal Server Error');
                break;
            case 503:
            case '503':
                header(Protocol::get() . ' 503 Service Unavailable');
                break;
            default:
                break;
        }
        return true;
    }

}
