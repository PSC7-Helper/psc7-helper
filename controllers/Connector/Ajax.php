<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Controller\Connector;

use psc7helper\App\Ajax\Ajax_Abstract;
use psc7helper\App\Ajax\Ajax_Interface;
use psc7helper\App\File\FileHelper;

class Ajax extends Ajax_Abstract implements Ajax_Interface
{
    /**
     * search.
     *
     * @return array
     */
    public function cachesize()
    {
        $fileHelper = new FileHelper('../var/cache/');
        $foldersize = $fileHelper->getFoldersize();
        $converted = $fileHelper->convertSize($foldersize);
        $class = '';
        if ($foldersize <= 209715200) { //<200MB
            $class = 'btn-success';
        } else if ($foldersize > 209715200 && $foldersize <= 2147483648) { //> 200MB && <= 2GB
            $class = 'btn-warning';
        } else if ($foldersize > 2147483648) { //>2GB
            $class = 'btn-danger';
        } else {
            $class = 'btn-info';
        }
        echo json_encode(['size' => $converted, 'class' => $class]);
    }

}
