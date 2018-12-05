<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Dashboard_cronjob;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;
use psc7helper\App\Connector\CronjobHelper;
use psc7helper\App\Date\Date;

class Module extends Module_Abstract implements Module_Interface {

    /**
     * helper
     * @var object 
     */
    private $helper;

    /**
     * run
     * @return string
     */
    public function run() {
        $this->setPlaceholder('cardtitle', __('cronjob_cardtitle'), false);
        $this->helper = new CronjobHelper();
        $helper = $this->helper;
        $table = '<table class="table table-striped">' . PHP_EOL;
        $table.= '    <thead>' . PHP_EOL;
        $table.= '        <tr>' . PHP_EOL;
        $table.= '            <th scope="col">' . __('cronjob_table_cronjob') . '</th>' . PHP_EOL;        
        $table.= '            <th class="d-none d-sm-table-cell" scope="col">' . __('cronjob_table_end') . '</th>' . PHP_EOL;
        $table.= '            <th class="d-none d-md-table-cell" scope="col">' . __('cronjob_table_runtime') . '</th>' . PHP_EOL;
        $table.= '            <th scope="col">' . __('cronjob_table_status') . '</th>' . PHP_EOL;
        $table.= '        </tr>' . PHP_EOL;
        $table.= '    </thead>' . PHP_EOL;
        $table.= '    <tbody>' . PHP_EOL;
        foreach ($helper->getCronjobList() as $value) {
            $name = str_replace('PlentyConnector ', '', $value['name']);
            $next = '<span class="text-danger">NULL</span>';
            $nextTs = 0;
            if ($value['next'] != null) {
                $next = Date::getDate('d.m.Y H:i', Date::datetimeToTimestamp($value['next']));
                $nextTs = Date::datetimeToTimestamp($value['next']);
            }
            $start = '<span class="text-danger">NULL</span>';
            $startTs = 0;
            if ($value['start'] != null) {
                $start = Date::getDate('d.m.Y H:i', Date::datetimeToTimestamp($value['start']));
                $startTs = Date::datetimeToTimestamp($value['start']);
            }
            $active = $value['active'];
            $end = '<span class="text-danger" title="' . __('cronjob_status_null') . '">00.00.0000 00:00</span>';
            $endTs = 0;
            if ($value['end'] != null) {
                $end = Date::getDate('d.m.Y H:i', Date::datetimeToTimestamp($value['end']));
                $endTs = Date::datetimeToTimestamp($value['end']);
            }
            $runtime = ($endTs - $startTs);
            if ($runtime >= 60) {
                $runtime = round($runtime / 60, 0) . ' min.';
            } else {
                $runtime = $runtime . ' sec.';
            }
            if ($endTs == 0) {
                $runtime = '<span class="text-danger">NULL</span>';
            }
            $status = 0;
            switch ($helper->getStatus($name, $active, $nextTs)) {
                case 0:
                    $status = '<span class="badge badge-danger badge-psc7">' . __('cronjob_status_inactive') . '</span>';
                    break;
                case 1:
                    $status = '<span class="badge badge-success badge-psc7">' . __('cronjob_status_active') . '</span>';
                    break;
                case 2:
                    $status = '<span class="badge badge-danger badge-psc7">' . __('cronjob_status_notrunning') . '</span>';
                    break;
            }
            $table.= '        <tr>' . PHP_EOL;
            $table.= '            <td>' . $name . '</td>' . PHP_EOL;
            $table.= '            <td class="d-none d-sm-table-cell">' . $end . '</td>' . PHP_EOL;
            $table.= '            <td class="d-none d-md-table-cell">' . $runtime . '</td>' . PHP_EOL;
            $table.= '            <td>' . $status . '</td>' . PHP_EOL;
            $table.= '        </tr>' . PHP_EOL;
        }
        $table.= '    </tbody>' . PHP_EOL;
        $table.= '</table>' . PHP_EOL;
        $this->setPlaceholder('table', $table, true);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }
    
}
