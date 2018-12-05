<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Form;

use psc7helper\App\Session\Session;

class FormValidator {

    /**
     * post
     * @var array
     */
    private $post;

    /**
     * __construct
     * @param array $postArray
     */
    public function __construct($postArray) {
        $this->post = $postArray;
    }

    /**
     * isValid
     * @return boolean
     */
    public function isValid() {
        $post = $this->post;
        if (!array_key_exists('formkey', $post) || $post['formkey'] != Session::get('token')) {
            return false;
        }
        if (!array_key_exists('formsecret', $post) || $post['formsecret'] != '') {
            return false;
        }
        return true;
    }

}
