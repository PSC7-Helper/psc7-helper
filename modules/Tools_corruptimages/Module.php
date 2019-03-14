<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Tools_corruptimages;

use psc7helper\App\Connector\CommandHandler;
use psc7helper\App\Modules\Module_Abstract;
use psc7helper\App\Modules\Module_Interface;

class Module extends Module_Abstract implements Module_Interface
{
    /**
     * model.
     *
     * @var object
     */
    private $model;

    /**
     * cli.
     *
     * @var object
     */
    private $cli;

    /**
     * filesDatabase.
     *
     * @var array
     */
    private $filesDatabase;

    /**
     * list.
     *
     * @var array
     */
    private $list = [];

    /**
     * setProperties.
     *
     * @return $this
     */
    private function setProperties()
    {
        $this->model = new Model();
        $this->cli = new CommandHandler();
        $this->setFilesDatabase();
        $this->prepareList();

        return $this;
    }

    /**
     * run.
     *
     * @return string
     */
    public function run()
    {
        $this->setProperties();
        $this->setPlaceholder('cardtitle', __('corruptimages_cardtitle'), false);
        $cli = $this->cli;
        $list = $this->list;
        $table = '<table class="table table-striped">' . PHP_EOL;
        $table .= '    <thead>' . PHP_EOL;
        $table .= '        <tr>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col">' . __('corruptimages_imageid') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col">' . __('corruptimages_image') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col">' . __('corruptimages_shopwareid') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col">' . __('corruptimages_ordernumber') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col">' . __('corruptimages_plentyid') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col"></th>' . PHP_EOL;
        $table .= '        </tr>' . PHP_EOL;
        $table .= '    </thead>' . PHP_EOL;
        $table .= '    <tbody>' . PHP_EOL;
        foreach ($list as $value) {
            $table .= '        <tr>' . PHP_EOL;
            $table .= '            <td class="text-left">' . $value['imageid'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left"><a href="' . $value['path'] . '" target="_new">' . $value['image'] . '</a></td>' . PHP_EOL;
            $table .= '            <td class="text-left">' . $value['shopwareid'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left"><a title="" href="../search?sSearch=' . $value['ordernumber'] . '" target="_new">' . $value['ordernumber'] . '</a> ' . $value['active'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left">' . $value['plentyid'] . '</td>' . PHP_EOL;
            if ('not mapped' != $value['plentyid']) {
                $table .= '            <td class="text-left">' . PHP_EOL;
                $table .= '                <form action="{{formaction}}" method="post">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="formname" value="clicommand">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="formkey" value="{{formkey}}">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="formsecret" value="">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="command" value="singlesync">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="option_all" value="0">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="option_vvv" value="1">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="option_backlog" value="1">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="product" value="' . $value['ordernumber'] . '">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="type" value="hide">' . PHP_EOL;
                $table .= '                    <button class="btn btn-psc7" type="submit" data-toggle="tooltip" data-placement="top" title="' . $cli->getCommandAsString('singlesync', $value['ordernumber']) . '">' . __('ordersync_product') . '</button>' . PHP_EOL;
                $table .= '                </form>' . PHP_EOL;
                $table .= '            </td>' . PHP_EOL;
            } else {
                $table .= '            <td class="text-left">' . PHP_EOL;
                $table .= '                <form action="{{formaction}}" method="post">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="formname" value="clicommand">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="formkey" value="{{formkey}}">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="formsecret" value="">' . PHP_EOL;
                $table .= '                    <input type="hidden" name="command" value="swmediacleanup">' . PHP_EOL;
                $table .= '                    <button class="btn btn-psc7" type="submit" data-toggle="tooltip" data-placement="top" title="' . $cli->getCommandAsString('swmediacleanup') . '">' . __('ordersync_swmediacleanup') . '</button>' . PHP_EOL;
                $table .= '                </form>' . PHP_EOL;
                $table .= '            </td>' . PHP_EOL;
            }
            $table .= '        </tr>' . PHP_EOL;
        }
        $table .= '    </tbody>' . PHP_EOL;
        $table .= '</table>' . PHP_EOL;
        $this->setPlaceholder('table', $table, false);
        $this->setPlaceholder('info_text', __('corruptimages_info_text'), false);
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }

    /**
     * setFilesDatabase.
     *
     * @return $this
     */
    private function setFilesDatabase()
    {
        $model = $this->model;
        $list = $model->getImageList();
        $temp = [];
        foreach ($list as $value) {
            $temp[] = $value['img'] . '.' . $value['extension'];
        }
        $this->filesDatabase = $temp;

        return $this;
    }

    /**
     * prepareList.
     *
     * @return $this
     */
    private function prepareList()
    {
        $files = $this->filesDatabase;
        if (0 == count($files)) {
            return $this;
        }
        $result = [];
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
        if ($mediaService) {
            foreach ($files as $value) {
                $image = parse_url($mediaService->getUrl('media/image/' . $value), PHP_URL_PATH);
                if (! file_exists('../' . $image)) {
                    $id = $this->getIDFromImage($value);
                    $articleID = $this->getArticleIDFromImage($value);
                    $active = (1 === $this->getStatusByArticleID($articleID)) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>';
                    $ordernumber = $this->getOrdnernumberByArticleID($articleID);
                    $plentyID = $this->getPlentyIDByArticleID($articleID);
                    $objectidentifier = $this->getObjectIdentifier($articleID, 'Shopware');
                    $result[] = [
                        'imageid'             => $id,
                        'image'               => $value,
                        'path'                => $mediaService->getUrl('media/image/' . $value),
                        'shopwareid'          => $articleID,
                        'active'              => $active,
                        'ordernumber'         => $ordernumber,
                        'plentyid'            => $plentyID,
                        'objectIdentifier'    => $objectidentifier,
                    ];
                }
            }
        }
        $this->list = $result;

        return $this;
    }

    /**
     * getFromImage.
     *
     * @param string $image
     *
     * @return string
     */
    private function getIDFromImage($image)
    {
        $model = $this->model;
        $data = $model->getIDFromImage($image);
        if (! $data) {
            $data = 'NULL';
        }

        return $data;
    }

    /**
     * getArticleIDFromImage.
     *
     * @param string $image
     *
     * @return string
     */
    private function getArticleIDFromImage($image)
    {
        $model = $this->model;
        $data = $model->getArticleIDFromImage($image);
        if (! $data) {
            $data = 'NULL';
        }

        return $data;
    }

    /**
     * getStatusByArticleID.
     *
     * @param string $articleID
     *
     * @return string
     */
    private function getOrdnernumberByArticleID($articleID)
    {
        $model = $this->model;
        $data = $model->getOrdnernumberByArticleID($articleID);
        if (! $data) {
            $data = 'NULL';
        }

        return $data;
    }

    /**
     * getOrdnernumberByArticleID.
     *
     * @param string $articleID
     *
     * @return string
     */
    private function getStatusByArticleID($articleID)
    {
        $model = $this->model;
        $data = $model->getStatusByArticleID($articleID);
        if (! $data) {
            $data = 'NULL';
        }

        return $data;
    }

    /**
     * getPlentyIDByArticleID.
     *
     * @param string $articleID
     *
     * @return string
     */
    private function getPlentyIDByArticleID($articleID)
    {
        $model = $this->model;
        $data = $model->getPlentyIDByArticleID($articleID);
        if (! $data) {
            $data = 'NULL';
        }

        return $data;
    }

    /**
     * getObjectIdentifier.
     *
     * @param string $adapterIdentifier
     * @param string $adapterName
     *
     * @return string
     */
    private function getObjectIdentifier($adapterIdentifier, $adapterName)
    {
        $model = $this->model;

        return $model->getObjectIdentifier($adapterIdentifier, $adapterName);
    }
}
