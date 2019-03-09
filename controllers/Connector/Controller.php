<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Controller\Connector;

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
        $page = $this->renderPage('index');

        return $page;
    }

    /**
     * identitys.
     *
     * @return string
     */
    public function identitys()
    {
        $this->setTemplate('identitys');
        $page = $this->renderPage('index');

        return $page;
    }

    /**
     * logfiles.
     *
     * @return string
     */
    public function logfiles()
    {
        $this->setTemplate('logfiles');
        $page = $this->renderPage('index');

        return $page;
    }

    /**
     * cronjobs.
     *
     * @return string
     */
    public function cronjobs()
    {
        $this->setTemplate('cronjobs');
        $page = $this->renderPage('index');

        return $page;
    }

    /**
     * cronjobsedit.
     *
     * @return string
     */
    public function cronjobsedit()
    {
        $this->setTemplate('cronjobsedit');
        $page = $this->renderPage('index');

        return $page;
    }
}
