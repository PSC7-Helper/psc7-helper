<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\Connector;

use psc7helper\App\Connector\ConnectorHelper;
use psc7helper\App\Connector\Model;

class CommandHandler {

    /**
     * SHEBANG
     */
    const SHEBANG = PHP_PATH;

    /**
     * CONSOLEPATH
     */
    const CONSOLEPATH = '../bin/console';

    /**
     * OPTION_ALL
     */
    const OPTION_ALL = '--all';

    /**
     * OPTION_VVV
     */
    const OPTION_VVV = '-vvv';

    /**
     * OPTION_BACKLOG
     */
    const OPTION_BACKLOG = '--disableBacklog';

    /**
     * model
     * @var object 
     */
    private $model;

    /**
     * helper
     * @var object 
     */
    private $helper;

    /**
     * commandlist
     * @var array 
     */
    private $commandlist = array();

    /**
     * product
     * @var string
     */
    private $product;

    /**
     * preparedCommands
     * @var array 
     */
    public $preparedCommands = array();

    /**
     * __construct
     */
    public function __construct() {
        $this->model = new Model();
        $this->helper = new ConnectorHelper();
    }

    /**
     * addCommand
     * @param string $command
     * @param string $product
     * @return $this
     */
    public function addCommand($command, $product = '') {
        $this->commandlist[] = $command;
        if ($product != '') {
            $this->product = $product;
        }
        return $this;
    }

    /**
     * getCommandAsString
     * @param string $command
     * @return string
     */
    public function getCommandAsString($command) {
        $result = '';
        $this->commandlist = array();
        $this->preparedCommands = array();
        $this->commandlist[] = $command;
        $this->prepareCommands();
        $list = $this->preparedCommands;
        foreach ($list as $value) {
            $result.= $value;
        }
        return $result;
    }

    /**
     * prepareCommands
     * @return $this
     */
    public function prepareCommands() {
        $helper = $this->helper;
        $whitelist = $helper->commandWhitelist();
        $commandlist = $this->commandlist;
        foreach ($commandlist as $command) {
            $add = true;
            $prepare = self::SHEBANG . ' ' . self::CONSOLEPATH . ' ' . $this->findCommand($command);
            if ($command == 'singlesync') {
                $product = $this->product;
                $objIdent = $this->findObjectIdentifierByReference($product);
                if ($product && $objIdent) {
                    $prepare.= ' ' . $objIdent;
                } else {
                    $add = false;
                }
            }
            if (array_key_exists('all', $whitelist[$command]) && $whitelist[$command]['all']) {
                $prepare.= ' ' . self::OPTION_ALL;
            } if (array_key_exists('vvv', $whitelist[$command]) && $whitelist[$command]['vvv']) {
                $prepare.= ' ' . self::OPTION_VVV;
            } if (array_key_exists('backlog', $whitelist[$command]) && $whitelist[$command]['backlog']) {
                $prepare.= ' ' . self::OPTION_BACKLOG;
            }
            if ($add) {
                $this->preparedCommands[] = $prepare;
            }
        }
        return $this;
    }

    /**
     * findCommand
     * @param type $search
     * @return string
     */
    private function findCommand($search) {
        $command = false;
        switch ($search) {
            case 'backlog_info':
                $command = 'plentyconnector:backlog:info';
                break;
            case 'backlog_process':
                $command = 'plentyconnector:backlog:process';
                break;
            case 'process_product':
                $command = 'plentyconnector:process Product';
                break;
            case 'process_stock':
                $command = 'plentyconnector:process Stock';
                break;
            case 'process_order':
                $command = 'plentyconnector:process Order';
                break;
            case 'process_category':
                $command = 'plentyconnector:process Category';
                break;
            case 'mapping':
                $command = 'plentyconnector:mapping';
                break;
            case 'swcacheclear':
                $command = 'sw:cache:clear';
                break;
            case 'swcronlist':
                $command = 'sw:cron:list';
                break;
            case 'swcronrun':
                $command = 'sw:cron:run';
                break;
            case 'swmediacleanup':
                $command = 'sw:media:cleanup';
                break;
            case 'swthumbnailcleanup':
                $command = 'sw:thumbnail:cleanup';
                break;
            case 'singlesync':
                $command = 'plentyconnector:process Product';
                break;
        }
        return $command;
    }

    /**
     * findObjectIdentifierByReference
     * @param string $reference
     * @return string
     */
    public function findObjectIdentifierByReference($reference) {
        $model = $this->model;
        $objectIdentifier = $model->findObjectIdentifierByReference($reference);
        if (!$objectIdentifier) {
            return false;
        }
        return $objectIdentifier;
    }

}
