<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Systeminfo_connectorversion;

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
        $this->setPlaceholder('cardtitle', __('connectorversion_cardtitle'), false);
        $version = false;
        $pluginXml = '../custom/plugins/PlentyConnector/plugin.xml';
        $reader = new \XMLReader();
        $doc = new \DOMDocument();
        if (file_exists($pluginXml) && $reader->open($pluginXml)) {
            while ($reader->read()) {
                if (\XMLReader::ELEMENT == $reader->nodeType && 'plugin' == $reader->name) {
                    $node = simplexml_import_dom($doc->importNode($reader->expand(), true));
                    $version = $node->version;
                }
            }
            $reader->close();
        }
        if ($version) {
            $this->setPlaceholder('version', $version . ' ', false);
        } else {
            $this->setPlaceholder('version', 'x.x.x', false);
        }
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }
}
