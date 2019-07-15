<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\File;

class FileHelper {

    /**
     * filepath
     * @var string 
     */
    private $filepath;

    /**
     * __construct.
     *
     * @param array $filepath
     */
    public function __construct($filepath = false) {
        $this->filepath = $filepath;
    }

    /**
     * getFoldersize
     * @param string $dirpath
     * @return string
     */
    public function getFoldersize($dirpath = false) {
        $path = ($dirpath) ? $dirpath : $this->filepath;
        if (!$path) {
            return '0';
        }
        $preparePath = rtrim($path, '/') . '/';
        if (!is_dir($preparePath)) {
            return '0';
        }
        $total = 0;
        
        foreach (glob(rtrim($preparePath, '/').'/*', GLOB_NOSORT) as $each) {
            $total += is_file($each) ? filesize($each) : $this->getFoldersize($each);
        }
        
        return $total;
    }

    /**
     * convertSize
     * @param int $bytes
     * @return string
     */
    public function convertSize($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 0) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 0) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 0) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

}
