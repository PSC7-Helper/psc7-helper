<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Systeminfo_load;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface
{
    /**
     * getLoadByProcstat.
     *
     * @return string
     */
    public function getLoadByProcstat()
    {
        $os = strtoupper(substr(PHP_OS, 0, 3));
        if (! function_exists('exec') || ! is_callable('exec') || 'LIN' != $os) {
            return false;
        }
        $procStat1 = false;
        $procStat2 = false;
        exec('cat /proc/stat', $procStat1);
        if (0 == count($procStat1)) {
            return false;
        }
        $cat1 = explode(' ', preg_replace('!cpu +!', '', $procStat1[0]));
        sleep(1);
        exec('cat /proc/stat', $procStat2);
        $cat2 = explode(' ', preg_replace('!cpu +!', '', $procStat2[0]));
        $difference = [];
        $difference['user'] = $cat2[0] - $cat1[0];
        $difference['nice'] = $cat2[1] - $cat1[1];
        $difference['sys'] = $cat2[2] - $cat1[2];
        $difference['idle'] = $cat2[3] - $cat1[3];
        $total = array_sum($difference);
        $usage = round(($total - $difference['idle']) / $total * 100, 2);

        return $usage;
    }

    /**
     * getLoadBySysload.
     *
     * @return string
     */
    public function getLoadBySysload()
    {
        $os = strtoupper(substr(PHP_OS, 0, 3));
        if (! function_exists('sys_getloadavg') || ! is_callable('sys_getloadavg') || 'LIN' != $os) {
            return false;
        }
        $load = sys_getloadavg();
        if (! $load) {
            return false;
        }

        return $load[0] . ' ' . $load[1] . ' ' . $load[2];
    }

    /**
     * getLoadByUptime.
     *
     * @return bool
     */
    public function getLoadByUptime()
    {
        $os = strtoupper(substr(PHP_OS, 0, 3));
        if (! function_exists('shell_exec') || ! is_callable('shell_exec') || 'LIN' != $os) {
            return false;
        }
        $uptime = shell_exec('uptime');
        if (null == $uptime) {
            return false;
        }
        $load = explode(' ', $uptime);

        return substr($load[13], -1) . ' ' . substr($load[14], -1) . ' ' . substr($load[15], -1);
    }

    /**
     * getWinCpuload.
     *
     * @return string
     */
    public function getWinCpuload()
    {
        $os = strtoupper(substr(PHP_OS, 0, 3));
        if (! function_exists('shell_exec') || ! is_callable('shell_exec') || 'WIN' != $os) {
            return false;
        }
        $percent = false;
        exec('wmic cpu get loadpercentage', $percent);

        return $percent[1];
    }

    /**
     * run.
     *
     * @return string
     */
    public function run()
    {
        $this->setPlaceholder('cardtitle', __('load_cardtitle'), false);
        $load = __('load_pleasewait');

        $this->setPlaceholder('load', $load . ' ', false);
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
