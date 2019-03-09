<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Ajax;

interface Ajax_Interface
{
    /**
     * __construct.
     *
     * @param string $path
     * @param string $action
     * @param string $param
     */
    public function __construct($path, $action, $param = false);
}
