<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Systeminfo_webserver;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface
{
    /**
     * webserver.
     *
     * @return array
     */
    private function webserver()
    {
        $webserver = [];
        $software = '';
        if (isset($_SERVER['SERVER_SOFTWARE']) && is_string($_SERVER['SERVER_SOFTWARE'])) {
            $software = filter_input(INPUT_SERVER, 'SERVER_SOFTWARE', FILTER_SANITIZE_SPECIAL_CHARS);
        }
        if (preg_match('/apache/i', $software)) {
            $split = explode('/', $software);
            if (count($split) > 1) {
                $version = explode(' ', $split[1]);
            } else {
                $version = ['x.x.x'];
            }
            $webserver['software'] = 'Apache';
            $webserver['version'] = $version[0];
        } elseif (preg_match('/nginx/i', $software)) {
            $split = explode('/', $software);
            if (count($split) > 1) {
                $version = explode(' ', $split[1]);
            } else {
                $version = ['x.x.x'];
            }
            $webserver['software'] = 'NginX';
            $webserver['version'] = $version[0];
        }

        return $webserver;
    }

    /**
     * run.
     *
     * @return string
     */
    public function run()
    {
        $this->setPlaceholder('cardtitle', __('webserver_cardtitle'), false);
        $webserver = $this->webserver();
        $this->setPlaceholder('version', $webserver['version'], false);
        $this->setPlaceholder('software', $webserver['software'], false);
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
