<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Ajax;

use psc7helper\App\Config\Lang;
use psc7helper\App\Header\Header;
use psc7helper\App\System;

abstract class Ajax_Abstract implements Ajax_Interface
{
    /**
     * path.
     *
     * @var string
     */
    protected $path;

    /**
     * action.
     *
     * @var string
     */
    protected $action;

    /**
     * param.
     *
     * @var string
     */
    protected $param;

    /**
     * __construct.
     *
     * @param string $path
     * @param string $action
     * @param string $param
     */
    public function __construct($path, $action, $param = false)
    {
        $this->path = $path;
        $this->action = $action;
        $this->param = ($param) ? $param : false;
        if ($this->isActionExists($action)) {
            $this->setLang();
            $this->$action();
        } else {
            $this->error(404);

            return false;
        }
    }

    /**
     * isActionExists.
     *
     * @param string $action
     *
     * @return bool
     */
    public function isActionExists($action)
    {
        if (! method_exists($this, $action)) {
            $this->error();

            return false;
        }

        return true;
    }

    /**
     * setLang.
     *
     * @return $this
     */
    private function setLang()
    {
        $lang = Lang::get('lang');
        $path = $this->path;
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
     * error.
     *
     * @return bool
     */
    public function error()
    {
        Header::send(404);
        System::log('error: action not found in ' . __FILE__ . ' on line ' . __LINE__, false);
        exit('Request not found');
    }
}
