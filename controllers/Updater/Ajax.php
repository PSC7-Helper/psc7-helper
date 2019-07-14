<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Controller\Updater;

use psc7helper\App\Ajax\Ajax_Abstract;
use psc7helper\App\Ajax\Ajax_Interface;

class Ajax extends Ajax_Abstract implements Ajax_Interface
{
    /**
     * search.
     *
     * @return array
     */
    public function search()
    {
        sleep(1);
        $version = $this->versionCompare();
        if (1 == $version) {
            echo json_encode([
                'message' => 'Update gefunden...',
                'update'  => 1,
            ]);

            return;
        } else {
            echo json_encode([
                'message' => 'Kein Update gefunden!',
                'update'  => 0,
            ]);

            return;
        }
    }

    /**
     * versionCompare.
     *
     * @return int
     */
    private function versionCompare()
    {
        $thisVersion = file_get_contents(ROOT_PATH . DS . 'VERSION');
        $githubVersion = file_get_contents('https://raw.githubusercontent.com/PSC7-Helper/psc7-helper/master/VERSION');
        if ($thisVersion && $githubVersion) {
            $this->thisVersion = $thisVersion;
            $this->githubVersion = $githubVersion;

            return version_compare($githubVersion, $thisVersion);
        } else {
            return false;
        }
    }

    /**
     * update.
     *
     * @return array
     */
    public function update()
    {
        sleep(1);
        $output = '';
        if (function_exists('shell_exec')) {
            $output = shell_exec('git pull origin/master');
        }
        if ('' != $output) {
            echo json_encode([
                'message' => 'git pull...',
                'output'  => $output,
                'update'  => 1,
            ]);

            return;
        } else {
            echo json_encode([
                'message' => 'Ein Fehler ist aufgetreten!',
                'output'  => $output,
                'update'  => 0,
            ]);

            return;
        }
    }

    public function check()
    {
        sleep(1);
        $version = $this->versionCompare();
        if (1 == $version) {
            echo json_encode([
                'message' => 'Update installiert',
                'update'  => 1,
            ]);

            return;
        } else {
            echo json_encode([
                'message' => 'Kein Update gefunden!',
                'update'  => 0,
            ]);

            return;
        }
    }
}
