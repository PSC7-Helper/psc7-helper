<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App;

interface Bootstrap_Interface
{
    /**
     * __construct.
     *
     * @param array $requests
     */
    public function __construct($requests);

    /**
     * run.
     */
    public function run();
}
