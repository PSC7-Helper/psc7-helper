<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_buttons;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;
use psc7helper\App\Connector\ConnectorHelper;
use psc7helper\App\Connector\CommandHandler;

class Module extends Module_Abstract implements Module_Interface {

    /**
     * helper
     * @var object 
     */
    private $helper;

    /**
     * cli
     * @var object 
     */
    private $cli;

    /**
     * run
     * @return string
     */
    public function run() {
        $this->setPlaceholder('cardtitle', __('buttons_cardtitle'), false);
        $this->helper = new ConnectorHelper();
        $this->cli = new CommandHandler();
        $helper = $this->helper;
        $cli = $this->cli;
        $backlogCount = $helper->getBacklogCount();
        $this->setPlaceholder('backlogcount', ' ', false);
        if ($backlogCount == 0) {
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
        $this->setPlaceholder('btn_process_stock', __('buttons_btn_process_stock'), false);
        $this->setPlaceholder('btn_process_stock_title', $cli->getCommandAsString('process_stock'), false);
        $this->setPlaceholder('btn_process_order', __('buttons_btn_process_order'), false);
        $this->setPlaceholder('btn_process_order_title', $cli->getCommandAsString('process_order'), false);
        $this->setPlaceholder('btn_process_category', __('buttons_btn_process_category'), false);
        $this->setPlaceholder('btn_process_category_title', $cli->getCommandAsString('process_category'), false);
        $this->setPlaceholder('btn_mapping', __('buttons_btn_mapping'), false);
        $this->setPlaceholder('btn_mapping_title', $cli->getCommandAsString('mapping'), false);
        $this->setPlaceholder('btn_swcacheclear', __('buttons_btn_sw_cache_clear'), false);
        $this->setPlaceholder('btn_swcacheclear_title', $cli->getCommandAsString('swcacheclear'), false);
        $this->setPlaceholder('btn_swcronlist', __('buttons_btn_sw_cron_list'), false);
        $this->setPlaceholder('btn_swcronlist_title', $cli->getCommandAsString('swcronlist'), false);
        $this->setPlaceholder('btn_swcronrun', __('buttons_btn_sw_cron_run'), false);
        $this->setPlaceholder('btn_swcronrun_title', $cli->getCommandAsString('swcronrun'), false);
        $this->setPlaceholder('btn_swmediacleanup', __('buttons_btn_sw_media_cleanup'), false);
        $this->setPlaceholder('btn_swmediacleanup_title', $cli->getCommandAsString('swmediacleanup'), false);
        $this->setPlaceholder('btn_swthumbnailcleanup', __('buttons_btn_sw_thumbnail_cleanup'), false);
        $this->setPlaceholder('btn_swthumbnailcleanup_title', $cli->getCommandAsString('swthumbnailcleanup'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }

}