<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Dashboard_phpinfo;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface {

    /**
     * run
     * @return string
     */
    public function run() {
        $this->setPlaceholder('cardtitle', __('phpinfo_cardtitle'), false);
        $this->setPlaceholder('noiframe', __('phpinfo_noiframe'), false);
        $this->setPlaceholder('noiframe_link', __('phpinfo_noiframe_link'), false);
        $this->setPlaceholder('noiframe_text', __('phpinfo_noiframe_text'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }

}
