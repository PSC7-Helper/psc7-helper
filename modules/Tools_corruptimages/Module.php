<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Tools_corruptimages;

use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;
use psc7helper\App\Http\Request;
use psc7helper\App\Header\Header;
use psc7helper\Module\Tools_corruptimages\Model;

class Module extends Module_Abstract implements Module_Interface {

    /**
     * requests
     * @var array 
     */
    private $requests;

    /**
     * model
     * @var object 
     */
    private $model;

    /**
     * filesDatabase
     * @var array 
     */
    private $filesDatabase;

    /**
     * list
     * @var array 
     */
    private $list = array();

    /**
     * setProperties
     * @return $this
     */
    private function setProperties() {
        $this->requests = Request::getArguments();
        $this->model = new Model();
        return $this;
    }

    /**
     * run
     * @return string
     */
    public function run() {
        $this->setProperties();
        $this->setPlaceholder('cardtitle', __('corruptimages_cardtitle'), false);
        $requests = $this->requests;
        $this->setFilesDatabase();
        $this->prepareList();
        $list = $this->list;
        $table = '<table class="table table-striped">' . PHP_EOL;
        $table.= '    <thead>' . PHP_EOL;
        $table.= '        <tr>' . PHP_EOL;
        $table.= '            <th class="text-left" scope="col">' . __('corruptimages_imageid') . '</th>' . PHP_EOL;
        $table.= '            <th class="text-left" scope="col">' . __('corruptimages_image') . '</th>' . PHP_EOL;
        $table.= '            <th class="text-left" scope="col">' . __('corruptimages_shopwareid') . '</th>' . PHP_EOL;
        $table.= '            <th class="text-left" scope="col">' . __('corruptimages_ordernumber') . '</th>' . PHP_EOL;
        $table.= '            <th class="text-left" scope="col">' . __('corruptimages_plentyid') . '</th>' . PHP_EOL;
        $table.= '        </tr>' . PHP_EOL;
        $table.= '    </thead>' . PHP_EOL;
        $table.= '    <tbody>' . PHP_EOL;
        foreach ($list as $value) {
            $table.= '        <tr>' . PHP_EOL;
            $table.= '            <td class="text-left">' . $value['imageid'] . '</td>' . PHP_EOL;
            $table.= '            <td class="text-left"><a href="' . $value['path'] . '" target="_new">' . $value['image'] . '</a></td>' . PHP_EOL;
            $table.= '            <td class="text-left">' . $value['shopwareid'] . '</td>' . PHP_EOL;
            $table.= '            <td class="text-left">' . $value['ordernumber'] . ' ' . $value['active'] . '</td>' . PHP_EOL;
            $table.= '            <td class="text-left">' . $value['plentyid'] . '</td>' . PHP_EOL;
            $table.= '        </tr>' . PHP_EOL;
        }
        $table.= '    </tbody>' . PHP_EOL;
        $table.= '</table>' . PHP_EOL;
        $this->setPlaceholder('table', $table, false);
        $this->setPlaceholder('info_text', __('corruptimages_info_text'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();
        return $module;
    }

    /**
     * setFilesDatabase
     * @return $this
     */
    private function setFilesDatabase() {
        $model = $this->model;
        $list = $model->getImageList();
        $temp = array();
        foreach ($list as $value) {
            $temp[] = $value['img'] . '.' . $value['extension'];
        }
        $this->filesDatabase = $temp;
        return $this;
    }

    /**
     * prepareList
     * @return $this
     */
    private function prepareList() {
        $files = $this->filesDatabase;
        if (count($files) == 0) {
            return $this;
        }
        $result = array();
        $autoloader = '../autoload.php';
        $application = '../engine/Shopware/Application.php';
        $mediaService = false;
        if (file_exists($autoloader) && file_exists($application)) {
            require_once $autoloader;
            require_once $application;
            $kernel = new \Shopware\Kernel('production', false);
            $kernel->boot(false);
            $config = $kernel->getConfig();
            $connection = \Shopware\Components\DependencyInjection\Bridge\Db::createPDO($config['db']);
            $container = $kernel->getContainer();
            $container->set('db_connection', $connection);
            $container->get('models');
            $mediaService = $container->get('shopware_media.media_service');
        }
        foreach ($files as $value) {
            $image = parse_url($mediaService->getUrl('media/image/' . $value), PHP_URL_PATH);
            if (!file_exists('../' . $image)) {
                $id = $this->getIDFromImage($value);
                $articleID = $this->getArticleIDFromImage($value);
                $active = ($this->getStatusByArticleID($articleID) === 1) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>';
                $ordernumber = $this->getOrdnernumberByArticleID($articleID);
                $plentyID = $this->getPlentyIDByArticleID($articleID);
                $result[] = array (
                    'imageid' => $id,
                    'image' => $value,
                    'path' => $mediaService->getUrl('media/image/' . $value),
                    'shopwareid' => $articleID,
                    'active' => $active,
                    'ordernumber' => $ordernumber,
                    'plentyid' => $plentyID
                );
            }
        }
        $this->list = $result;
        return $this;
    }

    /**
     * getFromImage
     * @param string $image
     * @return string
     */
    private function getIDFromImage($image) {
        $model = $this->model;
        $data = $model->getIDFromImage($image);
        if (!$data) {
            $data = 'NULL';
        }
        return $data;
    }

    /**
     * getArticleIDFromImage
     * @param string $image
     * @return string
     */
    private function getArticleIDFromImage($image) {
        $model = $this->model;
        $data = $model->getArticleIDFromImage($image);
        if (!$data) {
            $data = 'NULL';
        }
        return $data;
    }

    /**
     * getStatusByArticleID
     * @param string $articleID
     * @return string
     */
    private function getOrdnernumberByArticleID($articleID) {
        $model = $this->model;
        $data = $model->getOrdnernumberByArticleID($articleID);
        if (!$data) {
            $data = 'NULL';
        }
        return $data;
    }

    /**
     * getOrdnernumberByArticleID
     * @param string $articleID
     * @return string
     */
    private function getStatusByArticleID($articleID) {
        $model = $this->model;
        $data = $model->getStatusByArticleID($articleID);
        if (!$data) {
            $data = 'NULL';
        }
        return $data;
    }

    /**
     * getPlentyIDByArticleID
     * @param string $articleID
     * @return string
     */
    private function getPlentyIDByArticleID($articleID) {
        $model = $this->model;
        $data = $model->getPlentyIDByArticleID($articleID);
        if (!$data) {
            $data = 'NULL';
        }
        return $data;
    }

}
