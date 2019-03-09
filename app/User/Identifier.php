<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\User;

class Identifier
{
    /**
     * identifier.
     *
     * @var string
     */
    private $identifier;

    /**
     * __construct.
     */
    public function __construct()
    {
    }

    /**
     * generate.
     *
     * @param bool $complex
     *
     * @return $this
     */
    public function generate($complex = false)
    {
        $data = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
        $data .= gethostbyaddr(filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $data .= filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($complex) {
            $data .= bin2hex(random_bytes(32));
        }
        $this->identifier = substr(hash('ripemd256', $data), 0, 64);

        return $this;
    }

    /**
     * get.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
