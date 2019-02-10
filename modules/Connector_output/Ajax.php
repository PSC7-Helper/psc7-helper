<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Connector_output;

use psc7helper\App\Ajax\Ajax_Abstract;
use psc7helper\App\Ajax\Ajax_Interface;
use psc7helper\App\Session\Session;
use psc7helper\App\Connector\ConnectorHelper;
use psc7helper\App\Connector\CommandHandler;

class Ajax extends Ajax_Abstract implements Ajax_Interface {

    /**
     * ajax
     * @return string
     */
    public function ajax() {
        $command = Session::get('cli_command');
        $product = Session::get('cli_product');
        $optionAll = Session::get('cli_option_all');
        $optionVVV = Session::get('cli_option_vvv');
        $optionBacklog = Session::get('cli_option_backlog');
        $helper = new ConnectorHelper();
        if (!Session::get('cli_command') || !$helper->checkCommand($command, $optionAll, $optionVVV, $optionBacklog)) {
            $this->removeFromSession();
            print '<pre>Command not allowed</pre>';
            exit(1);
        }
        $cli = new CommandHandler();
        if ($product) {
            $cli->addCommand($command, $product)->prepareCommands();
        } else {
            $cli->addCommand($command)->prepareCommands();
        }
        if (!$cli->preparedCommands && $command != 'singlesync') {
            $this->removeFromSession();
            print '<pre>Command not found</pre>';
            exit(1);
        } elseif (!$cli->preparedCommands && $command == 'singlesync') {
            $this->removeFromSession();
            print '<pre>objectIdentifier not found for ' . $product . '</pre>';
            exit(1);
        }
        $os = strtoupper(substr(PHP_OS, 0, 3));
        if ($os == 'WIN') {
            print 'Not supported on windows machine';
            return false;
        }
        if (function_exists('popen') && is_callable('popen')) {
            while (@ob_end_flush());
            foreach ($cli->preparedCommands as $cliCommand) {
                if ($product) {
                    print 'ObjectIdentifier found for ' . $product . PHP_EOL . PHP_EOL;
                }
                print 'Execute: ' . $cliCommand . PHP_EOL . PHP_EOL;
                $proc = popen("$cliCommand 2>&1", 'r');
                while (!feof($proc)) {
                    print fread($proc, 4096);
                    @flush();
                }
                pclose($proc);
            }
            $this->removeFromSession();
            print PHP_EOL . 'done';
        } else if (function_exists('shell_exec') && is_callable('shell_exec'))  {
            foreach ($cli->preparedCommands as $cliCommand) {
                if ($product) {
                    print 'ObjectIdentifier found for ' . $product . PHP_EOL . PHP_EOL;
                }
                print 'Execute: ' . $cliCommand . PHP_EOL . PHP_EOL;
                $output = shell_exec("$cliCommand");
                print $output;
            }
            $this->removeFromSession();
            print PHP_EOL . 'done';
        } else {
            print 'php functions popen() and shell_exec() not allowd on your server';
        }
    }

    /**
     * removeFromSession
     * @return $this
     */
    private function removeFromSession() {
        Session::remove('cli_command');
        Session::remove('cli_product');
        Session::remove('cli_option_all');
        Session::remove('cli_option_vvv');
        Session::remove('cli_option_backlog');
        return $this;
    }

}
