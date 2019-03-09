<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Systeminfo_shopwareversion;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface
{
    /**
     * run.
     *
     * @return string
     */
    public function run()
    {
        $this->setPlaceholder('cardtitle', __('shopwareversion_cardtitle'), false);
        $version = '';
        $autoloader = '../autoload.php';
        $application = '../engine/Shopware/Application.php';
        if (file_exists($autoloader) && file_exists($application)) {
            require_once $autoloader;
            require_once $application;
            $version = \Shopware::VERSION;
        }
        if ('' != $version) {
            $this->setPlaceholder('version', $version, false);
        } else {
            $this->setPlaceholder('version', 'x.x.x', false);
        }
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
