<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Controller\Updater;

use psc7helper\App\Controllers\Controller_Abstract;
use psc7helper\App\Controllers\Controller_Interface;

class Controller extends Controller_Abstract implements Controller_Interface
{
    /**
     * index.
     *
     * @return string
     */
    public function index()
    {
        $this->setTemplate('index');
        $page = $this->renderPage('index-full');

        return $page;
    }
}
