<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Models;

use psc7helper\App\Database\Database;

abstract class Model_Abstract implements Model_Interface
{
    /**
     * database.
     *
     * @var Database
     */
    protected $database;

    /**
     * __construct.
     */
    public function __construct()
    {
        $this->database = new Database();
    }
}
