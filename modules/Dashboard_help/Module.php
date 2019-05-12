<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Dashboard_help;

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
        $this->setPlaceholder('help_1_header', __('help_1_header'), false);
        $this->setPlaceholder('help_1_text', __('help_1_text'), false);
        $this->setPlaceholder('help_2_header', __('help_2_header'), false);
        $this->setPlaceholder('help_2_text', __('help_2_text'), false);
        $this->setPlaceholder('help_3_header', __('help_3_header'), false);
        $this->setPlaceholder('help_3_text', __('help_3_text'), false);
        $this->setPlaceholder('help_4_header', __('help_4_header'), false);
        $this->setPlaceholder('help_4_text', __('help_4_text'), false);
        $this->setPlaceholder('help_5_header', __('help_5_header'), false);
        $this->setPlaceholder('help_5_text', __('help_5_text'), false);
        $this->setPlaceholder('help_6_header', __('help_6_header'), false);
        $this->setPlaceholder('help_6_text', __('help_6_text'), false);
        $this->setPlaceholder('help_7_header', __('help_7_header'), false);
        $this->setPlaceholder('help_7_text', __('help_7_text'), false);
        $this->setPlaceholder('help_8_header', __('help_8_header'), false);
        $this->setPlaceholder('help_8_text', __('help_8_text'), false);
        $this->setPlaceholder('help_9_header', __('help_9_header'), false);
        $this->setPlaceholder('help_9_text', __('help_9_text'), false);
        $this->setPlaceholder('help_10_header', __('help_10_header'), false);
        $this->setPlaceholder('help_10_text', __('help_10_text'), false);
        $this->setPlaceholder('help_11_header', __('help_11_header'), false);
        $this->setPlaceholder('help_11_text', __('help_11_text'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
