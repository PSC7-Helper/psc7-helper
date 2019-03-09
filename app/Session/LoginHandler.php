<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Session;

class LoginHandler
{
    /**
     * model.
     *
     * @var object
     */
    private $model;

    /**
     * userID.
     *
     * @var int
     */
    private $userID = false;

    /**
     * message.
     *
     * @var string
     */
    private $mesage;

    /**
     * __construct.
     */
    public function __construct()
    {
        $this->model = new Model();
    }

    /**
     * successful.
     *
     * @param string $username
     * @param string $password
     *
     * @return type
     */
    public function successful($username, $password)
    {
        if ($this->failedlogins($username) >= 3) {
            $this->mesage = '<div class="alert alert-danger">' . __('message_loginhandler_failedlogins') . '</div>';

            return [
                'status'  => false,
                'message' => $this->mesage,
            ];
        }
        if (! $this->validateUsername($username) || ! $this->verifyPassword($username, $password)) {
            $this->mesage = '<div class="alert alert-danger">' . __('message_loginhandler_invalid') . '</div>';

            return [
                'status'  => false,
                'message' => $this->mesage,
            ];
        }
        if (! $this->mesage && $this->userID) {
            Session::login($this->userID);
        }

        return [
            'status'  => true,
            'message' => '',
        ];
    }

    /**
     * validateUsername.
     *
     * @param string $username
     *
     * @return bool
     */
    private function validateUsername($username)
    {
        return (bool) $this->model->validateUsername($username);
    }

    /**
     * verifyPassword.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    private function verifyPassword($username, $password)
    {
        $passwordHash = $this->model->getPasswordHash($username);
        if (! $passwordHash) {
            return false;
        }
        if (password_verify($password, $passwordHash)) {
            $this->userID = $this->model->getUserID($username, $passwordHash);

            return true;
        }
        $this->setFailedlogin($username);

        return false;
    }

    /**
     * setFailedlogin.
     *
     * @param string $username
     *
     * @return bool
     */
    private function setFailedlogin($username)
    {
        return $this->model->setFailedlogin($username);
    }

    /**
     * failedlogins.
     *
     * @param string $username
     *
     * @return bool
     */
    private function failedlogins($username)
    {
        return (int) $this->model->failedlogins($username);
    }
}
