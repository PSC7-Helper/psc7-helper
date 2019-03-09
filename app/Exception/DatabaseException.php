<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Exception;

class DatabaseException extends Exception_Abstract
{
    /**
     * __construct.
     */
    public function __construct()
    {
        parent::__construct();
        $this->file = 'exception.log';
    }
}
