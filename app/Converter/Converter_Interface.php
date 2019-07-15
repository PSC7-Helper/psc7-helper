<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Converter;

interface Converter_Interface
{
    /**
     * __construct.
     *
     * @param string $type
     */
    public function __construct($type);

    /**
     * convert.
     *
     * @return $this
     */
    public function convert();

    /**
     * save.
     *
     * @return $this
     */
    public function save();
}
