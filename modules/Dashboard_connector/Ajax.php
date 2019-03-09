<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Dashboard_connector;

use psc7helper\App\Ajax\Ajax_Abstract;
use psc7helper\App\Ajax\Ajax_Interface;
use psc7helper\App\Connector\ConnectorHelper;

class Ajax extends Ajax_Abstract implements Ajax_Interface
{
    /**
     * ajax.
     *
     * @return string
     */
    public function count()
    {
        $helper = new ConnectorHelper();
        echo $helper->getBacklogCount() . ' ' . __('connector_backlog_text');
    }
}
