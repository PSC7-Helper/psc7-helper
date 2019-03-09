<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Connector;

class ConnectorHelper
{
    /**
     * BACKLOG_SUCCSESS.
     */
    const BACKLOG_SUCCESS = 625;

    /**
     * BACKLOG_WARNING.
     */
    const BACKLOG_WARNING = 1750;

    /**
     * BACKLOG_DANGER.
     */
    const BACKLOG_DANGER = 3500;

    /**
     * LOG_PATH.
     */
    const LOG_PATH = '..' . DS . 'var' . DS . 'log' . DS;

    /**
     * model.
     *
     * @var object
     */
    private $model;

    /**
     * __construct.
     */
    public function __construct()
    {
        $this->model = new Model();
    }

    /**
     * getBacklogCount.
     *
     * @return int
     */
    public function getBacklogCount()
    {
        return $this->model->getBacklogCount();
    }

    /**
     * countByObjectType.
     *
     * @param string $objectType
     * @param string $adapterName
     *
     * @return int
     */
    public function countByObjectType($objectType, $adapterName)
    {
        return $this->model->countByObjectType($objectType, $adapterName);
    }

    /**
     * getProductlistAsArray.
     *
     * @return array
     */
    public function getProductlistAsArray()
    {
        $list = [];
        $productlist = $this->model->getProductlistAsArray();
        foreach ($productlist as $value) {
            $list[] = $value['ordernumber'];
        }

        return $list;
    }

    /**
     * checkCommand.
     *
     * @param string $command
     * @param int    $all
     * @param int    $vvv
     * @param int    $backlog
     *
     * @return bool
     */
    public function checkCommand($command, $all, $vvv, $backlog)
    {
        $commandlist = $this->commandWhitelist();
        if (! array_key_exists($command, $commandlist)
            || $commandlist[$command]['all'] !== $all
            || $commandlist[$command]['vvv'] !== $vvv
            || $commandlist[$command]['backlog'] !== $backlog) {
            return false;
        }

        return true;
    }

    /**
     * commandlist.
     *
     * @return array
     */
    public function commandWhitelist()
    {
        $commands = [
            'backlog_info'         => ['all' => 0, 'vvv' => 0, 'backlog' => 0],
            'backlog_process'      => ['all' => 0, 'vvv' => 1, 'backlog' => 0],
            'process_product'      => ['all' => 0, 'vvv' => 1, 'backlog' => 1],
            'process_stock'        => ['all' => 0, 'vvv' => 1, 'backlog' => 1],
            'process_order'        => ['all' => 0, 'vvv' => 1, 'backlog' => 1],
            'process_category'     => ['all' => 0, 'vvv' => 1, 'backlog' => 1],
            'mapping'              => ['all' => 0, 'vvv' => 0, 'backlog' => 0],
            'swcacheclear'         => ['all' => 0, 'vvv' => 0, 'backlog' => 0],
            'swthemecachegenerate' => ['all' => 0, 'vvv' => 0, 'backlog' => 0],
            'swcronlist'           => ['all' => 0, 'vvv' => 0, 'backlog' => 0],
            'swcronrun'            => ['all' => 0, 'vvv' => 0, 'backlog' => 0],
            'swmediacleanup'       => ['all' => 0, 'vvv' => 0, 'backlog' => 0],
            'singlesync'           => ['all' => 0, 'vvv' => 1, 'backlog' => 1],
            'singlesync_order'     => ['all' => 0, 'vvv' => 1, 'backlog' => 1],
        ];

        return $commands;
    }

    /**
     * findIdentity.
     *
     * @param string $search
     * @param string $column
     *
     * @return bool|string
     */
    public function findIdentity($search, $column)
    {
        $list = $this->model->findIdentity($search, $column);
        if (! $list) {
            return false;
        }
        $result = '';
        $result .= '<table class="table">';
        $result .= '    <thead>';
        $result .= '        <tr>';
        $result .= '            <th>id</th>';
        $result .= '            <th>objectIdentifier</th>';
        $result .= '            <th>objectType</th>';
        $result .= '            <th>adapterIdentifier</th>';
        $result .= '            <th>adapterName</th>';
        $result .= '        </tr>';
        $result .= '    </thead>';
        $result .= '    <tbody>';
        foreach ($list as $value) {
            $result .= '        <tr>';
            $result .= '            <td>' . $value['id'] . '</td>';
            $result .= '            <td>' . $value['objectIdentifier'] . '</td>';
            $result .= '            <td>' . $value['objectType'] . '</td>';
            $result .= '            <td>' . $value['adapterIdentifier'] . '</td>';
            $result .= '            <td>' . $value['adapterName'] . '</td>';
            $result .= '        </tr>';
        }
        $result .= '    </tbody>';
        $result .= '</table>';

        return $result;
    }

    /**
     * getLogfileList.
     *
     * @return bool
     */
    public function getLogfileList()
    {
        $list = [];
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
     * getFilesize.
     *
     * @param string $filename
     *
     * @return int
     */
    public function getFilesize($filename)
    {
        $path = self::LOG_PATH;
        $filesize = filesize($path . $filename);
        $units = [
            'bytes', 'KB', 'MB', 'GB', 'TB',
        ];
        $fileweight = $filesize > 0 ? floor(log($filesize, 1024)) : 0;

        return number_format($filesize / pow(1024, $fileweight), 2, '.', ',') . ' ' . $units[$fileweight];
    }

    /**
     * getLogfileByValue.
     *
     * @param string $logfile
     * @param string $value
     *
     * @return string
     */
    public function getLogfileByValue($logfile, $value)
    {
        if (false === strpos($logfile, 'plentyconnector_production-')) {
            return false;
        }
        $log = '';
        $handle = fopen('..' . DS . 'var' . DS . 'log' . DS . $logfile, 'r');
        if ($handle) {
            while (false !== ($line = fgets($handle))) {
                if (false !== strpos($line, $value)) {
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
