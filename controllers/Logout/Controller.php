<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Controller\Logout;

use psc7helper\App\Controllers\Controller_Abstract;
use psc7helper\App\Controllers\Controller_Interface;
use psc7helper\App\Header\Header;
use psc7helper\App\Session\Session;

class Controller extends Controller_Abstract implements Controller_Interface
{
    /**
     * index.
     *
     * @return boolen
     */
    public function index()
    {
        Session::destroy();
        Session::set('logout', 1);
        Header::send(301, 'index.php?controller=login');
    }
}
