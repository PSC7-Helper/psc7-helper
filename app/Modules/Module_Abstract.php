<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Modules;

use psc7helper\App\Common\Escape;
use psc7helper\App\Config\Lang;
use psc7helper\App\System;
use psc7helper\App\Template\TemplateSystem;

abstract class Module_Abstract implements Module_Interface
{
    /**
     * templatePath.
     *
     * @var string
     */
    protected $templatePath;

    /**
     * template.
     *
     * @var string
     */
    protected $template;

    /**
     * placeholder.
     *
     * @var array
     */
    protected $placeholder = [];

    /**
     * __construct.
     *
     * @param string $templatePath
     */
    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;
        $this->setLang();
    }

    /**
     * setLang.
     *
     * @return $this
     */
    private function setLang()
    {
        $lang = Lang::get('lang');
        $path = str_replace('/template', '', $this->templatePath);
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
     * setPlaceholder.
     *
     * @param string $key
     * @param string $value
     * @param bool   $html
     *
     * @return $this
     */
    public function setPlaceholder($key, $value, $html = false)
    {
        if (is_array($value)) {
            $value = implode(' ', $value);
            System::log('setPlaceholder: array to string conversion by key ' . $key . ' in ' . __FILE__ . ' on line ' . __LINE__, false);
        }
        $value = strval($value) . ' ';
        if (empty($key) || empty($value)) {
            System::log('setPlaceholder: empty key (' . $key . ') or value (' . $value . ') in ' . __FILE__ . ' on line ' . __LINE__, false);

            return $this;
        }
        $this->placeholder[] = [
            Escape::key($key) => Escape::value($value, 0, $html),
        ];

        return $this;
    }

    /**
     * setTemplate.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setTemplate($name)
    {
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
     * renderPage.
     *
     * @return bool
     */
    public function renderModule()
    {
        if ($this->template) {
            $templateSystem = new TemplateSystem();
            $template = $this->templatePath . DS . $this->template;
            $placeholder = $this->placeholder;
            $templateSystem->readTemplate($template)->setPlaceholder($placeholder);

            return $templateSystem->renderTemplate();
        } else {
            System::log('renderModule: template not set in ' . __FILE__ . ' on line ' . __LINE__, false);

            return false;
        }
    }

    /**
     * run.
     *
     * @return bool
     */
    public function run()
    {
        System::log('run: Function not overriden in ' . __FILE__ . ' on line ' . __LINE__, false);
    }
}
