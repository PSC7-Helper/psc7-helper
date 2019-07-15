<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Converter;

class ConverterFactory
{
    /**
     * converter.
     *
     * @var object
     */
    protected $converter;

    /**
     * __construct.
     */
    public function __construct()
    {
    }

    /**
     * setConverter.
     *
     * @param string $class
     * @param string $type
     *
     * @return $this
     */
    public function setConverter($class, $type)
    {
        if (! is_string($class) || ! is_string($type)) {
            return $this;
        }
        $this->converter = new $class($type);

        return $this;
    }

    /**
     * getConverter.
     *
     * @return object
     */
    public function getConverter()
    {
        return $this->converter;
    }
}
