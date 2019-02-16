<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Tools_output;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;
use psc7helper\App\Connector\ConnectorHelper;
use psc7helper\App\Http\Request;
use psc7helper\App\Form\FormValidator;
use psc7helper\App\Session\Session;
use psc7helper\Module\Connector_output\Ajax;

class Module extends Module_Abstract implements Module_Interface {

    /**
     * helper
     * @var ojbect 
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
        $this->setProperties();
        $helper = $this->helper;
        $requests = $this->requests;
        $form = $this->formValidator;
        $post = false;
        $this->setPlaceholder('output', __('output_status'), false);
        if ($form->isValid() && array_key_exists('formname', $requests) && $requests['formname'] == 'clicommand' && array_key_exists('command', $requests)) {
            $post = true;
            $command = $requests['command'];
            if (array_key_exists('product', $requests)) {
                $product = $requests['product'];
            } else {
                $product = false;
            }
            $optionAll = (array_key_exists('option_all', $requests) && $requests['option_all']) ? 1 : 0;
            $optionVVV = (array_key_exists('option_vvv', $requests) && $requests['option_vvv']) ? 1 : 0;
            $optionBacklog = (array_key_exists('option_backlog', $requests) && $requests['option_backlog']) ? 1 : 0;
            if (!$helper->checkCommand($command, $optionAll, $optionVVV, $optionBacklog)) {
                $this->setPlaceholder('output', __('output_notallowed'), false);
                $this->setTemplate('view');
                $module = $this->renderModule();
                return $module;
            }
            Session::set('cli_command', $command);
            Session::set('cli_product', $product);
            Session::set('cli_option_all', $optionAll);
            Session::set('cli_option_vvv', $optionVVV);
            Session::set('cli_option_backlog', $optionBacklog);
            sleep(1);
        }
        if ($post) {
            $ajax = new Ajax(MODULES_PATH . DS . 'connector_output' . DS . 'Ajax.php', 'ajax', false);
            $output = $ajax->output();
            $this->setPlaceholder('output', $output, false);
            $this->setTemplate('view');
        } else {
            $this->setTemplate('view');
        }
        $module = $this->renderModule();
        return $module;
    }

}
