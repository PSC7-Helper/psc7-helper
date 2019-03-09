<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Systeminfo_load;

use psc7helper\App\Ajax\Ajax_Abstract;
use psc7helper\App\Ajax\Ajax_Interface;

class Ajax extends Ajax_Abstract implements Ajax_Interface
{
    /**
     * load.
     *
     * @return string
     */
    public function load()
    {
        $load = '';
        $module = new Module(false);
        if ($module->getLoadByProcstat()) {
            $load .= '[Linux] System ' . $module->getLoadByProcstat() . '%';
        }
        if ($module->getLoadBySysload()) {
            $load .= ' - ' . $module->getLoadBySysload();
        } elseif ($module->getLoadByUptime()) {
            $load .= ' - ' . $module->getLoadByUptime();
        }
        if ($module->getWinCpuload()) {
            $load .= '[Windows] CPU ' . $module->getWinCpuload() . '%';
        }
        if ('' == $load) {
            $load = __('load_notsupported');
        }
        echo $load;
    }
}
