<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Systeminfo_phpini;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface
{
    private function getPhpinilist()
    {
        $list = '';
        $list .= 'memory_limit = ' . ini_get('memory_limit') . '<br>';
        $list .= 'max_execution_time = ' . ini_get('max_execution_time') . '<br>';
        $list .= 'upload_max_filesize = ' . ini_get('upload_max_filesize') . '<br>';
        $list .= 'post_max_size = ' . ini_get('post_max_size') . '<br>';
        $list .= 'allow_url_fopen = ' . ini_get('allow_url_fopen') . '<br>';
        $list .= 'file_uploads = ' . ini_get('file_uploads') . '';

        return $list;
    }

    /**
     * run.
     *
     * @return string
     */
    public function run()
    {
        $this->setPlaceholder('cardtitle', __('phpini_cardtitle'), false);
        $list = $this->getPhpinilist();
        $this->setPlaceholder('list', $list, true);
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
