<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Controller\Install;

use psc7helper\App\Controllers\Controller_Abstract;
use psc7helper\App\Controllers\Controller_Interface;
use psc7helper\App\Session\Session;
use psc7helper\App\Form\FormValidator;
use psc7helper\App\Header\Header;

class Controller extends Controller_Abstract implements Controller_Interface {

    /**
     * index
     * @return string
     */
    public function index() {
        $requests = $this->requests;
        $form = new FormValidator($requests);
        $installLock = ROOT_PATH . DS . 'var' . DS . 'install.lock';
        if ($form->isValid() && array_key_exists('formname', $requests) && $requests['formname'] == 'install' && array_key_exists('phppath', $requests)) {
            if ($requests['phppath'] != '') {
                $config = ROOT_PATH . DS . 'config' . DS . 'config.tpl';
                $filestream = file_get_contents($config);
                $content = str_replace("define('PHP_PATH', 'php')", "define('PHP_PATH', '" . $requests['phppath'] . "')", $filestream);
                $fh = fopen(ROOT_PATH . DS . 'config.php', "w+");
                fwrite($fh, $content);
                fclose($fh);
                $fh2 = fopen($installLock, "w+");
                fwrite($fh2, date('Y-m-d H:i:s'));
                fclose($fh2);
                Header::send(301, 'index.php');
            }
        }
        $this->setPlaceholder('body_class', 'login-body', false);
        $this->setPlaceholder('index_h1', __('index_h1'), false);
        $this->setPlaceholder('index_label_phppath', __('index_label_phppath'), false);
        $this->setPlaceholder('index_input_phppath', __('index_input_phppath'), false);
        $clipath = 'php';
        $this->setPlaceholder('index_input_value', $clipath, false);
        if (function_exists('exec') && is_callable('exec')) {
            $option = false;
            exec("php ../bin/console 2>&1", $option);
            if (count($option) > 0) {
                $this->setPlaceholder('exec_info', 'Try to call: php ../bin/console ...'."\n".'Result: ' . implode(' ', $option), false);
            } else {
                $this->setPlaceholder('exec_info', 'Error: path incorrect', false);
            }
        }
        $this->setPlaceholder('index_info', __('index_info'), false);
        $this->setPlaceholder('index_button_submit', __('index_button_submit'), false);
        $this->setTemplate('index');
        $page = $this->renderPage('index-full');
        return $page;
    }

}
