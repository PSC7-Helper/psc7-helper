<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Http;

use psc7helper\App\Common\Escape;

class Protocol
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
    public static function get()
    {
        return Escape::input('SERVER_PROTOCOL', 'SERVER', 'string');
    }
}
