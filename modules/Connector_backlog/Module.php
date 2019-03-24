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
        $this->setPlaceholder('backlogcount', ' ', false);
        if ($backlogCount > 0 && $backlogCount <= ConnectorHelper::BACKLOG_SUCCESS) {
            $this->setPlaceholder('backlogcount', '<span id="backlogcount" class="badge badge-success badge-psc7big w-100">' . (string) $backlogCount . ' ' . __('backlog_backlog_text') . '</span>', true);
        } elseif ($backlogCount > ConnectorHelper::BACKLOG_SUCCESS && $backlogCount <= ConnectorHelper::BACKLOG_WARNING) {
            $this->setPlaceholder('backlogcount', '<span id="backlogcount" class="badge badge-warning badge-psc7big w-100">' . (string) $backlogCount . ' ' . __('backlog_backlog_text') . '</span>', true);
        } elseif ($backlogCount > ConnectorHelper::BACKLOG_WARNING && $backlogCount <= ConnectorHelper::BACKLOG_DANGER) {
            $this->setPlaceholder('backlogcount', '<span id="backlogcount" class="badge badge-danger badge-psc7big w-100">' . (string) $backlogCount . ' ' . __('backlog_backlog_text') . '</span>', true);
        } elseif ($backlogCount >= ConnectorHelper::BACKLOG_DANGER) {
            $this->setPlaceholder('backlogcount', '<span id="backlogcount" class="badge badge-danger badge-psc7big w-100">' . (string) $backlogCount . ' ' . __('backlog_backlog_text') . '</span>', true);
        } else {
            $this->setPlaceholder('backlogcount', '<span id="backlogcount" class="badge badge-success badge-psc7big w-100">' . __('backlog_backlog_empty') . '</span>', true);
        }
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
