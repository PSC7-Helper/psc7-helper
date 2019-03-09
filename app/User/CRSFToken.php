<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\User;

class CRSFToken
{
    /**
     * token.
     *
     * @var string
     */
    private $token;

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
        $data = null;
        if ($complex) {
            $data = (md5(uniqid(rand(), true)) . bin2hex(openssl_random_pseudo_bytes(32)));
        } else {
            $data = (md5(uniqid(rand(), true)));
        }
        $this->token = substr(hash('ripemd256', $data), 0, 64);

        return $this;
    }

    /**
     * getToken.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
