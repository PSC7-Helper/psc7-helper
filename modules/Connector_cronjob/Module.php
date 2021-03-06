<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_cronjob;

use psc7helper\App\Connector\CronjobHelper;
use psc7helper\App\Date\Date;
use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface
{
    /**
     * helper.
     *
     * @var object
     */
    private $helper;

    /**
     * run.
     *
     * @return string
     */
    public function run()
    {
        $this->setPlaceholder('cardtitle', __('cronjob_cardtitle'), false);
        $this->helper = new CronjobHelper();
        $helper = $this->helper;
        $table = '<table class="table table-striped">' . PHP_EOL;
        $table .= '    <thead>' . PHP_EOL;
        $table .= '        <tr>' . PHP_EOL;
        $table .= '            <th scope="col">' . __('cronjob_table_cronjob') . '</th>' . PHP_EOL;
        $table .= '            <th class="d-none d-md-table-cell" scope="col">' . __('cronjob_table_end') . '</th>' . PHP_EOL;
        $table .= '            <th class="d-none d-lg-table-cell" scope="col">' . __('cronjob_table_runtime') . '</th>' . PHP_EOL;
        $table .= '            <th scope="col">' . __('cronjob_table_status') . '</th>' . PHP_EOL;
        $table .= '            <th class="d-none d-lg-table-cell" scope="col">' . __('cronjob_table_interval') . '</th>' . PHP_EOL;
        $table .= '            <th class="d-none d-md-table-cell" scope="col">' . __('cronjob_table_next') . '</th>' . PHP_EOL;
        $table .= '            <th scope="col"></th>' . PHP_EOL;
        $table .= '        </tr>' . PHP_EOL;
        $table .= '    </thead>' . PHP_EOL;
        $table .= '    <tbody>' . PHP_EOL;
        foreach ($helper->getCronjobList() as $value) {
            $id = $value['id'];
            $name = str_replace('PlentyConnector ', '', $value['name']);
            $next = '<span class="text-danger">NULL</span>';
            $nextTs = 0;
            if (null != $value['next']) {
                $next = Date::getDate('d.m.Y H:i', Date::datetimeToTimestamp($value['end']) + (int) $value['interval']);
                $nextTs = Date::datetimeToTimestamp($value['next']);
            }
            $start = '<span class="text-danger">NULL</span>';
            $startTs = 0;
            if (null != $value['start']) {
                $start = Date::getDate('d.m.Y H:i', Date::datetimeToTimestamp($value['start']));
                $startTs = Date::datetimeToTimestamp($value['start']);
            }
            $interval = $value['interval'] . ' sec.';
            $active = $value['active'];
            $end = '<span class="text-danger" title="' . __('cronjob_status_null') . '">00.00.0000 00:00</span>';
            $endTs = 0;
            if (null != $value['end']) {
                $end = Date::getDate('d.m.Y H:i', Date::datetimeToTimestamp($value['end']));
                $endTs = Date::datetimeToTimestamp($value['end']);
            }
            //$informMail = $value['inform_mail'];
            $runtime = ($endTs - $startTs);
            if ($runtime >= 60) {
                $runtime = round($runtime / 60, 0) . ' min.';
            } else {
                $runtime = $runtime . ' sec.';
            }
            if (0 == $endTs) {
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
            $table .= '        <tr>' . PHP_EOL;
            $table .= '            <td>' . $name . '</td>' . PHP_EOL;
            $table .= '            <td class="d-none d-md-table-cell">' . $end . '</td>' . PHP_EOL;
            $table .= '            <td class="d-none d-lg-table-cell">' . $runtime . '</td>' . PHP_EOL;
            $table .= '            <td>' . $status . '</td>' . PHP_EOL;
            $table .= '            <td class="d-none d-lg-table-cell">' . $interval . '</td>' . PHP_EOL;
            $table .= '            <td class="d-none d-md-table-cell">' . $next . '</td>' . PHP_EOL;
            $table .= '            <td class="text-right"><a href="index.php?controller=connector&amp;action=cronjobsedit&amp;param=' . $id . '" title="' . __('cronjob_link_title') . '"><i class="fas fa-edit"></i></a></td>' . PHP_EOL;
            $table .= '        </tr>' . PHP_EOL;
        }
        $table .= '    </tbody>' . PHP_EOL;
        $table .= '</table>' . PHP_EOL;
        $this->setPlaceholder('table', $table, true);
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
