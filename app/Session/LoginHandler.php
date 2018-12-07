<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Session;

use psc7helper\App\Session\Model;

class LoginHandler {

    /**
     * model
     * @var object 
     */
    private $model;

    /**
     * userID
     * @var integer
     */
    private $userID = false;

    /**
     * message
     * @var string
     */
    private $mesage;

    /**
     * __construct
     */
    public function __construct() {
        $this->model = new Model();
    }

    /**
     * successful
     * @param string $username
     * @param string $password
     * @return type
     */
    public function successful($username, $password) {
        if (!$this->validateUsername($username) || !$this->verifyPassword($username, $password)) {
            $this->mesage = '<div class="alert alert-danger">' . __('message_loginhandler_invalid') . '</div>';
            return array(
                'status' => false,
                'message' => $this->mesage
            );
        }
        if (!$this->mesage && $this->userID) {
            Session::login($this->userID);
        }
        return array(
            'status' => true,
            'message' => ''
        );
    }

    /**
     * validateUsername
     * @param string $username
     * @return boolean
     */
    private function validateUsername($username) {
        return (bool) $this->model->validateUsername($username);
    }

    /**
     * verifyPassword
     * @param string $username
     * @param string $password
     * @return boolean
     */
    private function verifyPassword($username, $password) {
        $passwordHash = $this->model->getPasswordHash($username);
        if (!$passwordHash) {
            return false;
        }
        if (password_verify($password, $passwordHash)) {
            $this->userID = $this->model->getUserID($username, $passwordHash);
            return true;
        }
        return false;
    }

}