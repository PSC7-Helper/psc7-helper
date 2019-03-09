<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Controller\Login;

use psc7helper\App\Controllers\Controller_Abstract;
use psc7helper\App\Controllers\Controller_Interface;
use psc7helper\App\Form\FormValidator;
use psc7helper\App\Header\Header;
use psc7helper\App\Session\LoginHandler;
use psc7helper\App\Session\Session;

class Controller extends Controller_Abstract implements Controller_Interface
{
    /**
     * index.
     *
     * @return string
     */
    public function index()
    {
        if (true === $this->login) {
            Header::send(301, 'index.php?controller=dashboard');
        }
        $this->setPlaceholder('message', ' ', false);
        if (Session::get('logout')) {
            $this->setPlaceholder('message', '<div class="alert alert-success">' . __('message_logout') . '</div>', true);
            Session::remove('logout');
        }
        $requests = $this->requests;
        $form = new FormValidator($requests);
        if ($form->isValid() && array_key_exists('formname', $requests) && 'login' == $requests['formname']) {
            $login = new LoginHandler();
            $username = (array_key_exists('username', $requests)) ? $requests['username'] : false;
            $password = (array_key_exists('password', $requests)) ? $requests['password'] : false;
            $loginHandler = $login->successful($username, $password);
            if ($loginHandler['status']) {
                Header::send(301, 'index.php?controller=dashboard');
            } else {
                $this->setPlaceholder('message', $loginHandler['message'], true);
            }
        }
        $this->setPlaceholder('body_class', 'login-body', false);
        $this->setPlaceholder('index_h1', __('index_h1'), false);
        $this->setPlaceholder('index_input_username', __('index_input_username'), false);
        $this->setPlaceholder('index_input_password', __('index_input_password'), false);
        $this->setPlaceholder('index_info', __('index_info'), false);
        $this->setPlaceholder('index_button_submit', __('index_button_submit'), false);
        $this->setTemplate('index');
        $page = $this->renderPage('index-full');

        return $page;
    }
}
