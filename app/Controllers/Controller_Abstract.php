<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Controllers;

use psc7helper\App\Controllers\Controller_Interface;
use psc7helper\App\Config\Lang;
use psc7helper\App\Common\Escape;
use psc7helper\App\System;
use psc7helper\App\Template\TemplateSystem;
use psc7helper\App\Template\Placeholder;
use psc7helper\App\Config\Config;

abstract class Controller_Abstract implements Controller_Interface {

    /**
     * requests
     * @var array
     */
    protected $requests;

    /**
     * templatePath
     * @var string
     */
    protected $templatePath;

    /**
     * controllerName
     * @var string 
     */
    protected $controllerName;

    /**
     * action
     * @var string 
     */
    protected $action;

    /**
     * param
     * @var string 
     */
    protected $param;

    /**
     * login
     * @var boolean 
     */
    protected $login;

    /**
     * theme
     * @var string 
     */
    protected $theme;

    /**
     * indexTemplate
     * @var string 
     */
    protected $indexTemplate;

    /**
     * template
     * @var string 
     */
    protected $template;

    /**
     * placeholder
     * @var array 
     */
    protected $placeholder = array();

    /**
     * __construct
     * @param array $requests
     * @param string $templatePath
     * @param string $controllerName
     * @param string $action
     * @param string $param
     * @param bool $login
     * @param string $theme
     */
    public function __construct($requests, $templatePath, $controllerName, $action, $param, $login, $theme) {
        $this->requests = $requests;
        $this->templatePath = $templatePath;
        $this->controllerName = $controllerName;
        $this->action = $action;
        $this->param = $param;
        $this->login = $login;
        $this->theme = $theme;
        if ($this->isActionExists($action)) {
            $this->setLang()->setMeta();
            $this->$action();
        } else {
            $this->error(404);
            return false;
        }
    }

    /**
     * isActionExists
     * @param string $action
     * @return boolean
     */
    public function isActionExists($action) {
        if (!method_exists($this, $action)) {
            $this->error(404);
            return false;
        }
        return true;
    }

    /**
     * setLang
     * @return $this
     */
    private function setLang() {
        $lang = Lang::get('lang');
        $path = ROOT_PATH . DS . str_replace('/template', '', $this->templatePath);
        if (file_exists($path . DS . 'lang.json')) {
            $jsonFile = file_get_contents($path . DS . 'lang.json');
            $jsonDecode = json_decode($jsonFile, true);
            foreach ($jsonDecode as $key => $value) {
                if ($key === $lang) {
                    Lang::setMulti($value);
                }
            }
        }
        return $this;
    }

    /**
     * setMeta
     * @return $this
     */
    private function setMeta() {
        $action = $this->action;
        $this->setPlaceholder('meta_title', __('meta_title_' . $action), false);
        $this->setPlaceholder('meta_description', __('meta_description_' . $action), false);
        return $this;
    }

    /**
     * setPlaceholder
     * @param string $key
     * @param string $value
     * @param bool $html
     * @return $this
     */
    public function setPlaceholder($key, $value, $html = false) {
        if (empty($key) || empty($value)) {
            System::log('setPlaceholder: empty key or value in ' . __FILE__ . ' on line ' . __LINE__, false);
            return $this;
        }
        $this->placeholder[] = array(
            Escape::key($key) => Escape::value($value, 0, $html)
        );
        return $this;
    }

    /**
     * addPlaceholder
     * This is an alias for setPlaceholder
     * @param string $key
     * @param string $value
     * @param bool $html
     * @return $this
     */
    public function addPlaceholder($key, $value, $html = false) {
        $this->setPlaceholder($key, $value, $html);
        return $this;
    }

    /**
     * setIndexTemplate
     * @param string $name
     * @return $this
     */
    public function setIndexTemplate($name) {
        if (empty($name)) {
            System::log('setTemplate: empty name in ' . __FILE__ . ' on line ' . __LINE__, false);
            return $this;
        }
        $fileExtension = explode('.', $name);
        if (count($fileExtension) > 0) {
            $this->indexTemplate = $fileExtension[0] . '.phtml';
        } else {
            $this->indexTemplate = $name . '.phtml';
        }
        return $this;
    }

    /**
     * setTemplate
     * @param string $name
     * @return $this
     */
    public function setTemplate($name) {
        if (empty($name)) {
            System::log('setTemplate: empty name in ' . __FILE__ . ' on line ' . __LINE__, false);
            return $this;
        }
        $fileExtension = explode('.', $name);
        if (count($fileExtension) > 0) {
            $this->template = $fileExtension[0] . '.phtml';
        } else {
            $this->template = $name . '.phtml';
        }
        return $this;
    }

    /**
     * renderPage
     * @param string $index
     * @return boolean
     */
    public function renderPage($index = 'index') {
        $childTemplate = false;
        $ph = new Placeholder();
        $this->placeholder = array_merge($ph->get(), $this->placeholder);
        $templateSystem = new TemplateSystem();
        if ($this->template) {
            $template = ROOT_PATH . DS . $this->templatePath . DS . $this->template;
            if (!file_exists($template)) {
                System::log('renderPage: template ' . $template . ' not found in ' . __FILE__ . ' on line ' . __LINE__, false);
                $this->error(500);
                return false;
            }
            $templateSystem->readTemplate($template)->setPlaceholder($this->placeholder);
            $childTemplate = $templateSystem->renderTemplate();
        } else {
            System::log('renderPage: template not set in ' . __FILE__ . ' on line ' . __LINE__, false);
            $this->error(500);
            return false;
        }
        if ($index) {
            $idx = array('index', 'index-full', 'error');
            if (in_array($index, $idx)) {
                $this->setIndexTemplate($index);
            } else {
                $this->setIndexTemplate('index');
            }
            if ($this->indexTemplate) {
                $this->setPlaceholder('CONTROLLERACTION', $childTemplate, true);
                $indexTemplate = THEMES_PATH . DS . Config::get('theme') . DS . 'template' . DS . $this->indexTemplate;
                if (!file_exists($indexTemplate)) {
                    System::log('renderPage: indexTemplate ' . $indexTemplate . ' not found in ' . __FILE__ . ' on line ' . __LINE__, false);
                    $this->error(500);
                    return false;
                }
                $templateSystem->readTemplate($indexTemplate)->setPlaceholder($this->placeholder);
                print $templateSystem->renderTemplate();
                return true;
            }
        } else {
            print $templateSystem->renderTemplate();
            return true;
        }
    }

    /**
     * error
     * @param int $code
     * @return boolean
     */
    public function error($code) {
        $ph = new Placeholder();
        $placeholder = $ph->get();
        $templateSystem = new TemplateSystem();
        $template = ROOT_PATH . DS . 'themes' . DS . Config::get('theme') . DS . 'template' . DS . 'error' . $code . '.phtml';
        $templateSystem->readTemplate($template)->setPlaceholder($placeholder);
        $errorTemplate = $templateSystem->renderTemplate();
        exit($errorTemplate);
        return true;
    }

    /**
     * index
     */
    public function index() {
        return '';
    }

}
