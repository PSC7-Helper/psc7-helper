<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_singlesync;

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
        $this->setPlaceholder('cardtitle', __('singlesync_cardtitle'), false);
        $this->helper = new ConnectorHelper();        
        $helper = $this->helper;
        $productlist = $helper->getProductlistAsArray();
        $this->setPlaceholder('jsArrayItems', json_encode($productlist), false);
        $this->setPlaceholder('product_label', __('singlesync_poduct_label'), false);
        $this->setPlaceholder('product_placeholder', __('singlesync_poduct_placeholder'), false);
        $this->setPlaceholder('btn_text', __('singlesync_btn_text'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }

}
