<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Dashboard_connector;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;
use psc7helper\App\Connector\ConnectorHelper;

class Module extends Module_Abstract implements Module_Interface {

    /**
     * helper
     * @var object 
     */
    private $helper;

    /**
     * run
     * @return string
     */
    public function run() {
        $this->setPlaceholder('cardtitle', __('connector_cardtitle'), false);
        $this->helper = new ConnectorHelper();
        $helper = $this->helper;
        $backlogCount = $helper->getBacklogCount();
        $this->setPlaceholder('backlogcount', ' ', false);
        if ($backlogCount == 0) {
            $this->setPlaceholder('backlogcount', '<span class="badge badge-success badge-psc7big w-100">' . __('connector_backlog_empty') . '</span>', true);
        } else {
            if ($backlogCount > 0 && $backlogCount <= ConnectorHelper::BACKLOG_SUCCESS) {
                $this->setPlaceholder('backlogcount', '<span class="badge badge-success badge-psc7big w-100">' . (string) $backlogCount . ' ' . __('connector_backlog_text') . '</span>', true);
            } elseif ($backlogCount > ConnectorHelper::BACKLOG_SUCCESS && $backlogCount <= ConnectorHelper::BACKLOG_WARNING) {
                $this->setPlaceholder('backlogcount', '<span class="badge badge-warning badge-psc7big w-100">' . (string) $backlogCount . ' ' . __('connector_backlog_text') . '</span>', true);
            } elseif ($backlogCount > ConnectorHelper::BACKLOG_WARNING && $backlogCount <= ConnectorHelper::BACKLOG_DANGER) {
                $this->setPlaceholder('backlogcount', '<span class="badge badge-danger badge-psc7big w-100">' . (string) $backlogCount . ' ' . __('connector_backlog_text') . '</span>', true);
            }
        }
        $orderCountPA = $helper->countByObjectType('Order', 'PlentymarketsAdapter');
        $this->setPlaceholder('ordercount', '<span class="badge badge-info badge-psc7big w-100">' . (string) $orderCountPA . ' ' . __('connector_order_text') . '</span>', true);
        $categoryCountPA = $helper->countByObjectType('Category', 'PlentymarketsAdapter');
        $this->setPlaceholder('categorycount', '<span class="badge badge-info badge-psc7big w-100">' . (string) $categoryCountPA . ' ' .  __('connector_category_text') . '</span>', true);
        $manufacturerCountPA = $helper->countByObjectType('Manufacturer', 'PlentymarketsAdapter');
        $this->setPlaceholder('manufacturercount', '<span class="badge badge-info badge-psc7big w-100">' . (string) $manufacturerCountPA . ' ' . __('connector_manufacturer_text') . '</span>', true);
        $productCountPA = $helper->countByObjectType('Product', 'PlentymarketsAdapter');
        $this->setPlaceholder('productcount', '<span class="badge badge-info badge-psc7big w-100">' . (string) $productCountPA . ' ' . __('connector_product_text') . '</span>', true);
        $variationCountPA = $helper->countByObjectType('Variation', 'PlentymarketsAdapter');
        $this->setPlaceholder('variationcount', '<span class="badge badge-info badge-psc7big w-100">' . (string) $variationCountPA . ' ' . __('connector_variation_text') . '</span>', true);
        $mediaCountPA = $helper->countByObjectType('Media', 'PlentymarketsAdapter');
        $this->setPlaceholder('mediacount', '<span class="badge badge-info badge-psc7big w-100">' . (string) $mediaCountPA . ' ' . __('connector_media_text') . '</span>', true);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }

}