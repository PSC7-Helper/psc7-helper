<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_logfiles;

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
        $this->setPlaceholder('cardtitle', __('logfiles_cardtitle'), false);
        $this->setProperties();
        $helper = $this->helper;
        $requests = $this->requests;
        $form = $this->formValidator;
        $post = false;
        $this->setPlaceholder('file_stream', '<pre>' . __('logfiles_file_stream') . '</pre>', true);
        if ($form->isValid() && array_key_exists('formname', $requests) && $requests['formname'] == 'logfile' && isset($requests['logfile_file'])) {
            $post = $requests['logfile_file'];
            if ($entrys = $helper->getLogfileByValue($requests['logfile_file'], $requests['logfile_filter'])) {
                $this->setPlaceholder('file_stream', '<pre>' . $entrys . '</pre>', true);
            } else {
                $this->setPlaceholder('file_stream', '<pre>' . __('logfiles_file_nostream') . ' ' . $requests['logfile_filter'] . '</pre>', true);
            }
        }
        $this->setPlaceholder('logfile_label', __('logfiles_logfile_label'), false);
        $options = '';
        if ($list = $helper->getLogfileList()) {
            foreach ($list as $value) {
                $size = $helper->getFilesize($value);
                if ($post && $value == $requests['logfile_file']) {
                    $options .= '<option value="' . $value . '" selected>' . $value . ' (' . $size . ')</option>' . PHP_EOL;
                } else {
                    $options .= '<option value="' . $value . '">' . $value . ' (' . $size . ')</option>' . PHP_EOL;
                }
            }
        } else {
            $options .= '<option>'.__('logfiles_logfile_empty').'</option>' . PHP_EOL;
        }
        $this->setPlaceholder('logfile_options', $options, true);
        $this->setPlaceholder('logfile_help', __('logfiles_logfile_help'), false);
        $this->setPlaceholder('filter_label', __('logfiles_filter_label'), false);
        $filter = array('NOTICE','WARNING', 'ERROR');
        $filterOptions = '';
        foreach ($filter as $value) {
            if ($post && $value == $requests['logfile_filter']) {
                $filterOptions .= '<option value="' . $value . '" selected>' . $value . '</option>' . PHP_EOL;
            } else {
                $filterOptions .= '<option value="' . $value . '">' . $value . '</option>' . PHP_EOL;
            }
        }
        $this->setPlaceholder('filter_list', $filterOptions, false);
        $this->setPlaceholder('filter_help', __('logfiles_filter_help'), false);
        $this->setPlaceholder('btn_text', __('logfiles_btn_text'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }

}
