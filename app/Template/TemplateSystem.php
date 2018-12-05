<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Template;

use psc7helper\App\System;
use psc7helper\App\Config\Config;
use psc7helper\App\Common\Escape;

class TemplateSystem {

    /**
     * MAXFILESIZE
     */
    const MAXFILESIZE = 2097152;

    /**
     * stream
     * @var string 
     */
    protected $stream;

    /**
     * module
     * @var array
     */
    protected $module = array();

    /**
     * placeholder
     * @var array 
     */
    protected $placeholder = array();

    /**
     * tmp
     * @var string
     */
    protected $tmp;

    /**
     * __construct
     */
    public function __construct() {
        
    }

    /**
     * readTemplate
     * The relative path must be passed
     * @param string $template
     * @return string
     */
    public function readTemplate($template) {
        if (empty($template)) {
            System::log('readTemplate: No file given in ' . __FILE__ . ' on line ' . __LINE__);
            return $this;
        }
        $file = str_replace('\\', '/', $template);
        $this->stream = $this->loadStream($file);
        if ($this->stream) {
            $this->replaceFilePlaceholder($this->stream);
            $this->replaceModulePlaceholder($this->stream);
            if (Config::get('cacheHtml')) {
                $this->minifyStream($this->stream);
            }
            return $this;
        }
        return $this;
    }

    /**
     * loadStream
     * @param string $file
     * @return string
     */
    private function loadStream($file) {
        if (!file_exists($file) || !is_readable($file)) {
            System::log('loadStream: File not exsits or not readable in ' . __FILE__ . ' on line ' . __LINE__);
            return false;
        }
        $fileMimeType = mime_content_type($file);
        $allowedMimeTypes = $this->allowedMimeTypes();
        $filesize = filesize($file);
        if (in_array($fileMimeType, $allowedMimeTypes) && $filesize <= self::MAXFILESIZE) {
            return file_get_contents($file);
        }
        System::log('loadStream: File extension not allowed in ' . __FILE__ . ' on line ' . __LINE__);
        return $this;
    }

    /**
     * allowedMimeTypes
     * @return string
     */
    private function allowedMimeTypes() {
        $types = array(
            'text/html', 'text/plain',
        );
        return $types;
    }

    /**
     * replaceFilePlaceholder
     * @param string $stream
     * @return $this
     */
    private function replaceFilePlaceholder(&$stream) {
        $match = false;
        if (!preg_match_all('/\{\{file\:\:(.*)(|\:(.*))\}\}/U', $stream, $match)) {
            return $this;
        }
        $matches = count($match[0]);
        for ($i = 0; $i < $matches; $i++) {
            $stream = str_replace($match[0][$i], $this->loadStream($match[1][$i]), $stream);
        }
        return $this;
    }

    /**
     * replaceModulePlaceholder
     * @param string $stream
     * @return $this
     */
    private function replaceModulePlaceholder(&$stream) {
        $match = false;
        if (!preg_match_all('/\{\{module\:\:(.*)(|\:(.*))\}\}/U', $stream, $match)) {
            return $this;
        }
        $search = array();
        $replace = array();
        $matches = count($match[0]);
        for ($i = 0; $i < $matches; $i++) {
            $moduleName = $match[1][$i];
            if (isset($this->module[$moduleName])) {
                $search[] = $match[0][$i];
                $replace[] = $this->module[$moduleName]->run();
                continue;
            }
            $file = MODULES_PATH . DS . ucfirst($moduleName) . DS . 'Module.php';
            if (!file_exists($file)) {
                continue;
            }
            $search[] = $match[0][$i];
            $module = $this->requireModule($file, $moduleName);
            $this->module[$moduleName] = $module;
            $replace[] = $module->run();
        }
        $stream = str_replace($search, $replace, $stream);
        return $this;
    }

    /**
     * requireModule
     * @param string $file
     * @param string $className
     * @return object
     */
    private function requireModule($file, $className) {
        if (empty($file) || empty($className)) {
            System::log('replaceModulePlaceholder: No file or classname in ' . __FILE__ . ' on line ' . __LINE__);
            return false;
        }
        $argsTemplatePath = ROOT_PATH . DS . 'modules' . DS . ucfirst($className) . DS . 'template';
        require_once $file;
        $reflectionClass = new \ReflectionClass('psc7helper\\Module\\' . ucfirst($className) . '\\' . 'Module');
        $args = array($argsTemplatePath);
        $instance = $reflectionClass->newInstanceArgs($args);
        return $instance;
    }

    /**
     * minifyStream
     * @param string $stream
     * @return $this
     */
    private function minifyStream(&$stream) {
        $pregSearch = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/');
        $pregReplace = array('>', '<', '\\1', '',);
        $stream = preg_replace($pregSearch, $pregReplace, $stream);
        $strSearch = array('> <', '> ', ' <');
        $strReplace = array('><', '>', '<');
        $stream = str_replace($strSearch, $strReplace, $stream);
        return $this;
    }

    /**
     * setPlaceholder
     * @param array $placeholder
     * @return $this
     */
    public function setPlaceholder($placeholder) {
        foreach ($placeholder as $key => $item) {
            foreach ($item as $key => $value) {
                $this->placeholder[Escape::key($key)] = $value;
            }
        }
        return $this;
    }

    /**
     * renderTemplate
     * @return string
     */
    public function renderTemplate() {
        $stream = $this->stream;
        $placeholder = $this->placeholder;
        $search = array();
        $replace = array();
        foreach ($placeholder as $key => $value) {
            $search[] = '{{' . $key . '}}';
            $replace[] = $value;
        }
        $result = str_replace($search, $replace, $stream);
        return $result;
    }
 
    /**
     * renderModule
     * @param string $stream
     * @return string
     */
    public function renderModule($stream) {
        $this->tmp = $stream;
        if ($this->tmp) {
            $this->replaceModulePlaceholder($this->tmp);
            if (Config::get('minifyHtml')) {
                $this->minifyStream($this->tmp);
            }
            return $this;
        }
        return $this->tmp;
    }

    /**
     * renderList
     * @param string $list
     * @param string $stream
     * @param array $data (Key=>Value)
     * @return string
     */
    public function renderList($list, $stream, $data) {
        $match = false;
        $result = false;
        if (!preg_match_all('/(\{\{list::' . $list . '(|:(.*))\}\})(.*)(\{\{\/list::' . $list . '\}\})/Us', $stream, $match)) {
            return $stream;
        }
        $matches = count($match[0]);
        for ($i = 0; $i < $matches; $i++) {
            $listName = array();
            if (!empty($match[3][$i])) {
                $explode = explode(':', $match[3][$i]);
                if (count($explode) == 1) {
                    $explode[1] = '';
                }
            } else {
                $explode = array('ZEBRA0', 'ZEBRA1');
            }
            $entries = count($data);
            for ($i2 = 0; $i2 < $entries; $i2++) {
                if (($i % 2) == 0) {
                    $data[$i2]['ZEBRA'] = $explode[0];
                } else {
                    $data[$i2]['ZEBRA'] = $explode[1];
                }
                $data[$i2]['INDEX'] = $i2;
                $listName[] = $this->render($match[4][$i], $data[$i2]);
            }
            $listName = implode('', $listkName);
            $result = str_replace($match[0][$i], $listName, $stream);
        }
        return $result;
    }

}
