<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_backlog;

use psc7helper\App\Connector\ConnectorHelper;
use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface
{
    /**
     * helper.
     *
     * @var object
     */
    private $helper;

    /**
     * run.
     *
     * @return string
     */
    public function run()
    {
        $this->helper = new ConnectorHelper();
        $helper = $this->helper;
        $backlogCount = $helper->getBacklogCount();
        $this->setPlaceholder('backlogcount', $backlogCount, true);
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
