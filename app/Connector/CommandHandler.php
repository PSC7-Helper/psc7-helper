<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Connector;

use psc7helper\App\Session\Session;

class CommandHandler
{
    /**
     * SHEBANG.
     */
    const SHEBANG = PHP_PATH;

    /**
     * CONSOLEPATH.
     */
    const CONSOLEPATH = '../bin/console';

    /**
     * OPTION_ALL.
     */
    const OPTION_ALL = '--all';

    /**
     * OPTION_VVV.
     */
    const OPTION_VVV = '-vvv';

    /**
     * OPTION_BACKLOG.
     */
    const OPTION_BACKLOG = '--disableBacklog';

    /**
     * output.
     *
     * @var string
     */
    private $output;

    /**
     * model.
     *
     * @var object
     */
    private $model;

    /**
     * helper.
     *
     * @var object
     */
    private $helper;

    /**
     * commandlist.
     *
     * @var array
     */
    private $commandlist = [];

    /**
     * product.
     *
     * @var string
     */
    private $product;

    /**
     * preparedCommands.
     *
     * @var array
     */
    public $preparedCommands = [];

    /**
     * __construct.
     */
    public function __construct()
    {
        $this->model = new Model();
        $this->helper = new ConnectorHelper();
    }

    /**
     * addCommand.
     *
     * @param string $command
     * @param string $product
     *
     * @return $this
     */
    public function addCommand($command, $product = '')
    {
        $this->commandlist[] = $command;
        if ('' != $product) {
            $this->product = $product;
        }

        return $this;
    }

    /**
     * getCommandAsString.
     *
     * @param string $command
     * @param mixed  $product
     *
     * @return string
     */
    public function getCommandAsString($command, $product = false)
    {
        $result = '';
        if ($product) {
            $this->product = $product;
        }
        $this->commandlist = [];
        $this->preparedCommands = [];
        $this->commandlist[] = $command;
        $this->prepareCommands();
        $list = $this->preparedCommands;
        foreach ($list as $value) {
            $result .= $value;
        }
        return $result;
    }

    /**
     * prepareCommands.
     *
     * @return $this
     */
    public function prepareCommands()
    {
        $helper = $this->helper;
        $whitelist = $helper->commandWhitelist();
        $commandlist = $this->commandlist;
        foreach ($commandlist as $command) {
            $add = true;
            $prepare = self::SHEBANG . ' ' . self::CONSOLEPATH . ' ' . $this->findCommand($command);
            if ('singlesync' == $command) {
                $product = $this->product;
                $objIdent = $this->findObjectIdentifierByReference($product);
                if ($product && $objIdent) {
                    $prepare .= ' ' . $objIdent;
                } else {
                    $add = false;
                }
            }
            if ('singlesync_order' == $command) {
                $product = $this->product;
                if ($product) {
                    $prepare .= ' ' . $product;
                } else {
                    $add = false;
                }
            }
            if (array_key_exists('all', $whitelist[$command]) && $whitelist[$command]['all']) {
                $prepare .= ' ' . self::OPTION_ALL;
            }
            if (array_key_exists('vvv', $whitelist[$command]) && $whitelist[$command]['vvv']) {
                $prepare .= ' ' . self::OPTION_VVV;
            }
            if (array_key_exists('backlog', $whitelist[$command]) && $whitelist[$command]['backlog']) {
                $prepare .= ' ' . self::OPTION_BACKLOG;
            }
            if ($add) {
                $this->preparedCommands[] = $prepare;
            }
        }
        return $this;
    }

    /**
     * findCommand.
     *
     * @param type $search
     *
     * @return string
     */
    private function findCommand($search)
    {
        $command = false;
        switch ($search) {
            case 'backlog_info':
                $command = 'plentyconnector:backlog:info';
                break;
            case 'backlog_process':
                $command = 'plentyconnector:backlog:process';
                break;
            case 'process_manufacturer':
                $command = 'plentyconnector:process Manufacturer';
                break;
            case 'process_category':
                $command = 'plentyconnector:process Category';
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
            case 'process_order':
                $command = 'plentyconnector:process Media';
                break;
            case 'cleanup':
                $command = 'plentyconnector:cleanup';
                break;
            case 'mapping':
                $command = 'plentyconnector:mapping';
                break;
            case 'swcacheclear':
                $command = 'sw:cache:clear';
                break;
            case 'swthemecachegenerate':
                $command = 'sw:theme:cache:generate';
                break;
            case 'swmediacleanup':
                $command = 'sw:media:cleanup';
                break;
            case 'swrebuildseoindex':
                $command = 'sw:rebuild:seo:index';
                break;
            case 'swrebuildsearchindex':
                $command = 'sw:rebuild:search:index';
                break;
            case 'swcronlist':
                $command = 'sw:cron:list';
                break;
            case 'swcronrun':
                $command = 'sw:cron:run';
                break;
            case 'singlesync':
                $command = 'plentyconnector:process Product';
                break;
            case 'singlesync_order':
                $command = 'plentyconnector:process Order';
                break;
        }

        return $command;
    }

    /**
     * findObjectIdentifierByReference.
     *
     * @param string $reference
     *
     * @return string
     */
    public function findObjectIdentifierByReference($reference)
    {
        $model = $this->model;
        $objectIdentifier = $model->findObjectIdentifierByReference($reference);
        if (! $objectIdentifier) {
            return false;
        }

        return $objectIdentifier;
    }

    /**
     * handleCommand.
     *
     * @return bool
     */
    public function handleCommand()
    {
        $this->output = '';
        $os = strtoupper(substr(PHP_OS, 0, 3));
        if ('WIN' == $os) {
            $this->removeFromSession();
            $this->output .= 'Not supported on windows machine';

            return false;
        }
        $command = Session::get('cli_command');
        $product = Session::get('cli_product');
        $optionAll = Session::get('cli_option_all');
        $optionVVV = Session::get('cli_option_vvv');
        $optionBacklog = Session::get('cli_option_backlog');
        $helper = $this->helper;
        if (! Session::get('cli_command') || ! $helper->checkCommand($command, $optionAll, $optionVVV, $optionBacklog)) {
            $this->removeFromSession();
            $this->output .= '<pre>Command not allowed</pre>';

            return false;
        }
        $i = 0;
        $productList = [];
        if ($product) {
            $expl = explode(',', $product);
            if (count($expl) == 1) {
                $this->addCommand($command, $product)->prepareCommands();
            } else {
                foreach ($expl as $value) {
                    $this->addCommand($command, $value);
                    $productList[] = $value;
                }
                $this->prepareCommands();
            }
        } else {
            $this->addCommand($command)->prepareCommands();
        }
        if (! $this->preparedCommands && 'singlesync' != $command) {
            $this->removeFromSession();
            $this->output .= '<pre>Command not found</pre>';

            return false;
        } elseif (! $this->preparedCommands && 'singlesync' == $command) {
            $this->removeFromSession();
            $this->output .= '<pre>objectIdentifier not found for ' . $productList[0] . '</pre>';

            return false;
        }
        if ($this->isEnabled('shell_exec')) {
            foreach ($this->preparedCommands as $cliCommand) {
                if (count($productList) > 1 && $i > 0) {
                    $this->output .= PHP_EOL . PHP_EOL;
                }
                if ($product) {
                    if (count($productList) > 0) {
                        $this->output .= 'ObjectIdentifier found for ' . $productList[$i] . PHP_EOL . PHP_EOL;
                    } else {
                        $this->output .= 'ObjectIdentifier found for ' . $product . PHP_EOL . PHP_EOL;
                    }
                    $i++;
                }
                $this->output .= 'Execute: ' . $cliCommand . PHP_EOL . PHP_EOL;
                $output = shell_exec("$cliCommand");
                $this->output .= $output;
            }
            $this->removeFromSession();
            $this->output .= PHP_EOL . 'done';
        } else {
            $this->output .= 'php function shell_exec() not allowd';
        }

        return true;
    }

    /**
     * isEnabled.
     *
     * @param string $function
     *
     * @return bool
     */
    private function isEnabled($function)
    {
        $list = [];
        $count = 0;
        $disableFunctions = ini_get('disable_functions');
        if (strlen($disableFunctions) > 0) {
            $disableFunctions = str_replace([' ,', ', ', ' , '], ',', $disableFunctions);
            $expl = explode(',', $disableFunctions);
            foreach ($expl as $value) {
                $list[] = trim($value);
                ++$count;
            }
        } else {
            return true;
        }
        if ($count > 0) {
            return ! in_array($function, $list);
        }
    }

    /**
     * removeFromSession.
     *
     * @return $this
     */
    private function removeFromSession()
    {
        Session::remove('cli_command');
        Session::remove('cli_product');
        Session::remove('cli_option_all');
        Session::remove('cli_option_vvv');
        Session::remove('cli_option_backlog');

        return $this;
    }

    /**
     * output.
     *
     * @return string
     */
    public function output()
    {
        return $this->output;
    }
}
