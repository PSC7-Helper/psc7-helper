<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Global_updateinfo;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface
{
    /**
     * thisVersion.
     *
     * @var string
     */
    private $thisVersion;

    /**
     * githubVersion.
     *
     * @var string
     */
    private $githubVersion;

    /**
     * versionCompare.
     *
     * @return bool
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
     * run.
     *
     * @return string
     */
    public function run()
    {
        $version = $this->versionCompare();
        if (1 == $version) {
            $this->setPlaceholder('info', '<span class="badge badge-success">' . __('updateinfo_info') . ' (' . $this->githubVersion . ')</span>', true);
        } else {
            $this->setPlaceholder('info', ' ', false);
        }
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
