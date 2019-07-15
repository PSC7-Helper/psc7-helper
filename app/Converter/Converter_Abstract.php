<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Converter;

abstract class Converter_Abstract implements Converter_Interface
{
    /**
     * type.
     *
     * @var string
     */
    protected $type = '';

    /**
     * delimiter.
     *
     * @var string
     */
    protected $delimiter = ';';

    /**
     * enclosure.
     *
     * @var string
     */
    protected $enclosure = '"';

    /**
     * tmpname.
     *
     * @var string
     */
    protected $tmpname = '';

    /**
     * name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * msg.
     *
     * @var string
     */
    protected $msg = '';

    /**
     * error.
     *
     * @var bool
     */
    protected $error = false;

    /**
     * filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * UPLOAD_DIR.
     */
    const UPLOAD_DIR = ROOT_PATH . DS . 'files/upload';

    /**
     * DOWNLOAD_DIR.
     */
    const DOWNLOAD_DIR = ROOT_PATH . DS . 'files/download';

    /**
     * prepared.
     *
     * @var array
     */
    protected $prepared;

    /**
     * __construct.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = str_replace('Converter', '', $type);
        $this->filename = date('Ymd_His') . '_' . $this->type . '.csv';
    }

    /**
     * setDelimiter.
     *
     * @param string $delimiter
     *
     * @return $this
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * setEnclosure.
     *
     * @param string $enclosure
     *
     * @return $this
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * upload.
     *
     * @param _FILE $file
     *
     * @return $this
     */
    public function upload($file)
    {
        if (is_array($file) && UPLOAD_ERR_OK == $file['error'][0]) {
            $this->tmpname = $file['tmp_name'][0];
            $this->name = explode('.', $file['name'][0]);
            if ('csv' != end($this->name)) {
                $this->msg = __('csvconverter_upload_nocsv');
                $this->error = true;

                return $this;
            }
            move_uploaded_file($this->tmpname, self::UPLOAD_DIR . DS . $this->filename);
            $this->msg = __('csvconverter_upload_ok');
        } else {
            $this->getFileError($file['error'][0]);
        }

        return $this;
    }

    /**
     * getFileError.
     *
     * @param int $error
     *
     * @return $this
     */
    private function getFileError($error)
    {
        switch ($error) {
            case 4:
                $this->msg = __('csvconverter_upload_nofile');
                break;
            case 1:
                $this->msg = __('csvconverter_upload_tobig');
                break;
            case 2:
                $this->msg = __('csvconverter_upload_maxsize');
                break;
            case 3:
                $this->msg = __('csvconverter_upload_partical');
                break;
            case 6:
                $this->msg = __('csvconverter_upload_dirnotdefined');
                break;
            case 7:
                $this->msg = __('csvconverter_upload_notallowed');
                break;
            case 8:
                $this->msg = __('csvconverter_upload_phperror');
                break;
        }
        $this->error = true;

        return $this;
    }

    /**
     * getMessage.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->msg;
    }

    /**
     * uploadError.
     *
     * @return bool
     */
    public function uploadError()
    {
        return $this->error;
    }

    /**
     * readFile.
     *
     * @return array
     */
    protected function readFile()
    {
        $row = 1;
        $csvArr = [];
        if (false !== ($handle = fopen(self::UPLOAD_DIR . DS . $this->filename, 'r'))) {
            while (false !== ($data = fgetcsv($handle, 0, $this->delimiter, $this->enclosure))) {
                $csvArr[] = $data;
                ++$row;
            }
            fclose($handle);
        }

        return $csvArr;
    }

    /**
     * convert.
     *
     * @return $this
     */
    public function convert()
    {
        if (! $this->filename) {
            $this->msg = __('csvconverter_convertfailed');
            $this->error = true;

            return $this;
        }

        return $this;
    }

    /**
     * save.
     *
     * @return $this
     */
    public function save()
    {
        if (! is_array($this->prepared)) {
            $this->msg = __('csvconverter_savefailed');
            $this->error = true;

            return $this;
        }

        return $this;
    }
}
