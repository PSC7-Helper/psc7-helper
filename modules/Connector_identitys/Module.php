<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_identitys;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;
use psc7helper\App\Connector\ConnectorHelper;
use psc7helper\App\Http\Request;
use psc7helper\App\Form\FormValidator;

class Module extends Module_Abstract implements Module_Interface {

    /**
     * helper
     * @var object 
     */
    private $helper;

    /**
     * requests
     * @var array 
     */
    private $requests;

    /**
     * formValidato
     * @var object 
     */
    private $formValidator;

    /**
     * setProperties
     * @return $this
     */
    private function setProperties() {
        $this->helper = new ConnectorHelper();
        $this->requests = Request::getArguments();
        $requests = $this->requests;
        $this->formValidator = new FormValidator($requests);
        return $this;
    }

    /**
     * run
     * @return string
     */
    public function run() {
        $this->setPlaceholder('cardtitle', __('identitys_cardtitle'), false);
        $this->setProperties();
        $helper = $this->helper;
        $requests = $this->requests;
        $form = $this->formValidator;
        $this->setPlaceholder('output', '<pre>' . __('identitys_output') . '</pre>', true);
        if ($form->isValid() && array_key_exists('formname', $requests) && $requests['formname'] == 'identitys' && isset($requests['search'])) {
            if ($result = $helper->findIdentity($requests['search'], $requests['column'])) {
                $this->setPlaceholder('output', '<pre>' . $result . '</pre>', true);
            } else {
                $this->setPlaceholder('output', '<pre>' . __('identitys_output_empty') . ' ' . $requests['search'] . '</pre>', true);
            }
        }
        $this->setPlaceholder('search_label', __('identitys_search_label'), false);
        $this->setPlaceholder('search_help', __('identitys_search_help'), true);
        $this->setPlaceholder('column_label', __('identitys_column_label'), false);
        $this->setPlaceholder('column_help', __('identitys_column_help'), false);
        $this->setPlaceholder('btn_text', __('identitys_btn_text'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }

}
