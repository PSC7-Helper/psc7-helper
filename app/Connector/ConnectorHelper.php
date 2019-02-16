<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Connector;

use psc7helper\App\Connector\Model;

class ConnectorHelper {

    /**
     * BACKLOG_SUCCSESS
     */
    const BACKLOG_SUCCESS = 625;

    /**
     * BACKLOG_WARNING
     */
    const BACKLOG_WARNING = 1750;

    /**
     * BACKLOG_DANGER
     */
    const BACKLOG_DANGER = 3500;

    /**
     * LOG_PATH
     */
    const LOG_PATH = '..' . DS . 'var' . DS . 'log' . DS;

    /**
     * model
     * @var object 
     */
    private $model;

    /**
     * __construct
     */
    public function __construct() {
        $this->model = new Model();
    }

    /**
     * getBacklogCount
     * @return integer
     */
    public function getBacklogCount() {
        return $this->model->getBacklogCount();
    }

    /**
     * countByObjectType
     * @param string $objectType
     * @param string $adapterName
     * @return integer
     */
    public function countByObjectType($objectType, $adapterName) {
        return $this->model->countByObjectType($objectType, $adapterName);
    }

    /**
     * getProductlistAsArray
     * @return array
     */
    public function getProductlistAsArray() {
        $list = array();
        $productlist = $this->model->getProductlistAsArray();
        foreach ($productlist as $value) {
            $list[] = $value['ordernumber'];
        }
        return $list;
    }

    /**
     * checkCommand
     * @param string $command
     * @param integer $all
     * @param integer $vvv
     * @param integer $backlog
     * @return boolean
     */
    public function checkCommand($command, $all, $vvv, $backlog) {
        $commandlist = $this->commandWhitelist();
        if (!array_key_exists($command, $commandlist)
            || $commandlist[$command]['all'] !== $all
            || $commandlist[$command]['vvv'] !== $vvv
            || $commandlist[$command]['backlog'] !== $backlog) {
            return false;
        }
        return true;
    }

    /**
     * commandlist
     * @return array
     */
    public function commandWhitelist() {
        $commands = array(
            'backlog_info' => array('all' => 0, 'vvv' => 0, 'backlog' => 0),
            'backlog_process' => array('all' => 0, 'vvv' => 1, 'backlog' => 0),
            'process_product' => array('all' => 0, 'vvv' => 1, 'backlog' => 1),
            'process_stock' => array('all' => 0, 'vvv' => 1, 'backlog' => 1),
            'process_order' => array('all' => 0, 'vvv' => 1, 'backlog' => 1),
            'process_category' => array('all' => 0, 'vvv' => 1, 'backlog' => 1),
            'mapping' => array('all' => 0, 'vvv' => 0, 'backlog' => 0),
            'swcacheclear' => array('all' => 0, 'vvv' => 0, 'backlog' => 0),
            'swthemecachegenerate' => array('all' => 0, 'vvv' => 0, 'backlog' => 0),
            'swcronlist' => array('all' => 0, 'vvv' => 0, 'backlog' => 0),
            'swcronrun' => array('all' => 0, 'vvv' => 0, 'backlog' => 0),
            'swmediacleanup' => array('all' => 0, 'vvv' => 0, 'backlog' => 0),
            'singlesync' => array('all' => 0, 'vvv' => 1, 'backlog' => 1),
            'singlesync_order' => array('all' => 0, 'vvv' => 1, 'backlog' => 1)
        );
        return $commands;
    }

    /**
     * findIdentity
     * @param string $search
     * @param string $column
     * @return boolean|string
     */
    public function findIdentity($search, $column) {
        $list = $this->model->findIdentity($search, $column);
        if (!$list) {
            return false;
        }
        $result = '';
        $result.= '<table class="table">';
        $result.= '    <thead>';
        $result.= '        <tr>';
        $result.= '            <th>id</th>';
        $result.= '            <th>objectIdentifier</th>';
        $result.= '            <th>objectType</th>';
        $result.= '            <th>adapterIdentifier</th>';
        $result.= '            <th>adapterName</th>';
        $result.= '        </tr>';
        $result.= '    </thead>';
        $result.= '    <tbody>';
        foreach ($list as $value) {
            $result.= '        <tr>';
            $result.= '            <td>' . $value['id'] . '</td>';
            $result.= '            <td>' . $value['objectIdentifier'] . '</td>';
            $result.= '            <td>' . $value['objectType'] . '</td>';
            $result.= '            <td>' . $value['adapterIdentifier'] . '</td>';
            $result.= '            <td>' . $value['adapterName'] . '</td>';
            $result.= '        </tr>';
        }
        $result.= '    </tbody>';
        $result.= '</table>';
        return $result;
    }

    /**
     * getLogfileList
     * @return boolean
     */
    public function getLogfileList() {
        $list = array();
        $path = self::LOG_PATH;
        $glob = glob($path . 'plentyconnector_production-*.log');
        if ($glob) {
            foreach ($glob as $value) {
                $exp = explode('/', $value);
                $list[] = end($exp);
            }
        } else {
            return false;
        }
        rsort($list);
        return $list;
    }

    /**
     * getFilesize
     * @param string $filename
     * @return integer
     */
    public function getFilesize($filename) {
        $path = self::LOG_PATH;
        $filesize = filesize($path . $filename);
        $units = array (
            'bytes', 'KB', 'MB', 'GB', 'TB'
        );
        $fileweight = $filesize > 0 ? floor(log($filesize, 1024)) : 0;
        return number_format($filesize / pow(1024, $fileweight), 2, '.', ',') . ' ' . $units[$fileweight];
    }

    /**
     * getLogfileByValue
     * @param string $logfile
     * @param string $value
     * @return string
     */
    public function getLogfileByValue($logfile, $value) {
        if (strpos($logfile, 'plentyconnector_production-') === false) {
            return false;
        }
        $log = '';
        $handle = fopen('..' . DS . 'var' . DS . 'log' . DS . $logfile, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, $value) !== false) {
                    $log .= $line;
                }
            }
            fclose($handle);
        } else {
            return false;
        }
        return $log;
    }

}
