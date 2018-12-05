<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_cronjob_status;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;
use psc7helper\App\Connector\CronjobHelper;
use psc7helper\App\Http\Request;
use psc7helper\App\Form\FormValidator;
use psc7helper\App\Session\Session;

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
        $this->helper = new CronjobHelper();
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
        $this->setProperties();
        $helper = $this->helper;
        $requests = $this->requests;
        $form = $this->formValidator;
        if ($form->isValid() && array_key_exists('formname', $requests) && $requests['formname'] == 'cronjobstatus') {
            Session::set('dismisscronstatusalert', true);
        }
        if (!Session::get('dismisscronstatusalert')) {
            $cronjobSynchronize = $this->helper->getCronjobByName('PlentyConnector Synchronize');
            if ($cronjobSynchronize) {
                $difference = $helper->getTimeDeviation($cronjobSynchronize['next']);
                $maxDifference = 5400;
                if ($difference > $maxDifference) {
                    $timeDeviation = '<strong>' . round($difference / 60, 0) . ' min.</strong>';
                    $this->setPlaceholder('alert', __('cronjob_status_synchronize_text1') . ' ' . $timeDeviation . ' ' . __('cronjob_status_synchronize_text2'), true);
                    $this->setTemplate('view');
                    $module = $this->renderModule();
                    return $module;
                }
            }
        }
    }

}
