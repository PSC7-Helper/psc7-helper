<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_buttons;

use psc7helper\App\Connector\CommandHandler;
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
     * cli.
     *
     * @var object
     */
    private $cli;

    /**
     * run.
     *
     * @return string
     */
    public function run()
    {
        $this->setPlaceholder('cardtitle', __('buttons_cardtitle'), false);
        $this->helper = new ConnectorHelper();
        $this->cli = new CommandHandler();
        $helper = $this->helper;
        $cli = $this->cli;
        $backlogCount = $helper->getBacklogCount();
        $this->setPlaceholder('backlogcount', ' ', false);
        if (0 == $backlogCount) {
            $this->setPlaceholder('backlogcount', '<span class="badge badge-success">' . __('buttons_backlog_empty') . '</span>', true);
        } else {
            if ($backlogCount > 0 && $backlogCount <= ConnectorHelper::BACKLOG_SUCCESS) {
                $this->setPlaceholder('backlogcount', '<span class="badge badge-success">' . (string) $backlogCount . ' ' . __('buttons_backlog_text') . '</span>', true);
            } elseif ($backlogCount > ConnectorHelper::BACKLOG_SUCCESS && $backlogCount <= ConnectorHelper::BACKLOG_WARNING) {
                $this->setPlaceholder('backlogcount', '<span class="badge badge-warning">' . (string) $backlogCount . ' ' . __('buttons_backlog_text') . '</span>', true);
            } elseif ($backlogCount > ConnectorHelper::BACKLOG_WARNING && $backlogCount <= ConnectorHelper::BACKLOG_DANGER) {
                $this->setPlaceholder('backlogcount', '<span class="badge badge-danger">' . (string) $backlogCount . ' ' . __('buttons_backlog_text') . '</span>', true);
            }
        }
        $this->setPlaceholder('btn_backlog_info', __('buttons_btn_backlog_info'), false);
        $this->setPlaceholder('btn_backlog_info_title', $cli->getCommandAsString('backlog_info'), false);
        $this->setPlaceholder('btn_backlog_process', __('buttons_btn_backlog_process'), false);
        $this->setPlaceholder('btn_backlog_process_title', $cli->getCommandAsString('backlog_process'), false);
        $this->setPlaceholder('btn_process_product', __('buttons_btn_process_product'), false);
        $this->setPlaceholder('btn_process_product_title', $cli->getCommandAsString('process_product'), false);
        $this->setPlaceholder('btn_process_order', __('buttons_btn_process_order'), false);
        $this->setPlaceholder('btn_process_order_title', $cli->getCommandAsString('process_order'), false);
        $this->setPlaceholder('btn_process_stock', __('buttons_btn_process_stock'), false);
        $this->setPlaceholder('btn_process_stock_title', $cli->getCommandAsString('process_stock'), false);
        $this->setPlaceholder('btn_process_category', __('buttons_btn_process_category'), false);
        $this->setPlaceholder('btn_process_category_title', $cli->getCommandAsString('process_category'), false);
        $this->setPlaceholder('btn_process_manufacturer', __('buttons_btn_process_manufacturer'), false);
        $this->setPlaceholder('btn_process_manufacturer_title', $cli->getCommandAsString('process_manufacturer'), false);
        $this->setPlaceholder('btn_process_media', __('buttons_btn_process_media'), false);
        $this->setPlaceholder('btn_process_media_title', $cli->getCommandAsString('process_media'), false);
        $this->setPlaceholder('btn_cleanup', __('buttons_btn_cleanup'), false);
        $this->setPlaceholder('btn_cleanup_title', $cli->getCommandAsString('cleanup'), false);
        $this->setPlaceholder('btn_mapping', __('buttons_btn_mapping'), false);
        $this->setPlaceholder('btn_mapping_title', $cli->getCommandAsString('mapping'), false);
        $this->setPlaceholder('btn_swcacheclear', __('buttons_btn_swcacheclear'), false);
        $this->setPlaceholder('btn_swcacheclear_title', $cli->getCommandAsString('swcacheclear'), false);
        $this->setPlaceholder('btn_swthemecachegenerate', __('buttons_btn_swthemecachegenerate'), false);
        $this->setPlaceholder('btn_swthemecachegenerate_title', $cli->getCommandAsString('swthemecachegenerate'), false);
        $this->setPlaceholder('btn_swrebuildseoindex', __('buttons_btn_swrebuildseoindex'), false);
        $this->setPlaceholder('btn_swrebuildseoindex_title', $cli->getCommandAsString('swrebuildseoindex'), false);
        $this->setPlaceholder('btn_swrebuildsearchindex', __('buttons_btn_swrebuildsearchindex'), false);
        $this->setPlaceholder('btn_swrebuildsearchindex_title', $cli->getCommandAsString('swrebuildsearchindex'), false);
        $this->setPlaceholder('btn_swmediacleanup', __('buttons_btn_swmediacleanup'), false);
        $this->setPlaceholder('btn_swmediacleanup_title', $cli->getCommandAsString('swmediacleanup'), false);
        $this->setPlaceholder('btn_swcronlist', __('buttons_btn_swcronlist'), false);
        $this->setPlaceholder('btn_swcronlist_title', $cli->getCommandAsString('swcronlist'), false);
        $this->setPlaceholder('btn_swcronrun', __('buttons_btn_swcronrun'), false);
        $this->setPlaceholder('btn_swcronrun_title', $cli->getCommandAsString('swcronrun'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
