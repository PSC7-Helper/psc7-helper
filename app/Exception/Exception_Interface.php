<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Exception;

interface Exception_Interface
{
    /**
     * __construct.
     */
    public function __construct();

    /**
     * handle.
     *
     * @param object $ex
     * @param string $catch
     *
     * @return bool
     */
    public static function handle($ex, $catch);
}
