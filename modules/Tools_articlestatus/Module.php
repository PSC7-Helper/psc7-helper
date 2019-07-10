<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Tools_articlestatus;

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
     * productList.
     *
     * @var array
     */
    private $productList = [];

    /**
     * setProperties.
     *
     * @return $this
     */
    private function setProperties()
    {
        $this->model = new Model();
        $this->cli = new CommandHandler();

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
        $this->setPlaceholder('cardtitle', __('articlestatus_cardtitle'), false);
        $table = $this->renderTable();
        $this->setPlaceholder('table', $table, true);
        $btnSyncThemAll = '';
        if (count($this->productList) > 0) {
            $btnSyncThemAll = $this->renderBtnSyncThemAll();
        }
        $this->setPlaceholder('syncthemall', $btnSyncThemAll, true);
        $this->setTemplate('view');
        $module = $this->renderModule();

        return $module;
    }

    /**
     * getList.
     *
     * @param int $number
     *
     * @return $this
     */
    private function getList($number)
    {
        $list = [];
        $model = $this->model;
        $orders = $model->getArticles((int) $number);
        if (!$orders) {
            return $this;
        }
        foreach ($orders as $value) {
            $objectidentifier = $this->getObjectIdentifier($value['id'], 'Shopware');
            $list[] = [
                'id'               => $value['id'],
                'name'             => $value['name'],
                'ordernumber'      => $value['ordernumber'],
                'a_active'         => $value['a_active'],
                'ad_active'        => $value['ad_active'],
                'porderid'         => $this->getPlentyOrderid($objectidentifier),
                'objectIdentifier' => $objectidentifier,
            ];
            $this->productList[] = $value['ordernumber'];
        }

        return $list;
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

    /**
     * getPlentyOrderid.
     *
     * @param string $objectidentifier
     *
     * @return string
     */
    private function getPlentyOrderid($objectidentifier)
    {
        $model = $this->model;
        $poid = $model->getPlentyOrderid($objectidentifier);
        if (! $poid) {
            return 'not mapped';
        }

        return $poid;
    }

    /**
     * renderTable.
     *
     * @return string
     */
    private function renderTable()
    {
        $cli = $this->cli;
        $list = $this->getList(100);
        $table = '<table id="datatable-wp" class="table table-striped">' . PHP_EOL;
        $table .= '    <thead>' . PHP_EOL;
        $table .= '        <tr>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col">' . __('articlestatus_articleid') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left " scope="col">' . __('articlestatus_ordernumber') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left d-none d-lg-table-cell" scope="col">' . __('articlestatus_articlename') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col">' . __('articlestatus_active') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col"></th>' . PHP_EOL;
        $table .= '        </tr>' . PHP_EOL;
        $table .= '    </thead>' . PHP_EOL;
        $table .= '    <tbody>' . PHP_EOL;
        foreach ($list as $value) {
            $table .= '        <tr>' . PHP_EOL;
            $table .= '            <td class="text-left align-middle">' . $value['id'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left align-middle">' . $value['ordernumber'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left align-middle d-none d-lg-table-cell">' . substr($value['name'], 0, 60) . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left align-middle">' . $value['a_active'] . '/' . $value['ad_active'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left align-middle">' . PHP_EOL;
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
            $table .= '                    <button class="btn btn-psc7" type="submit" data-toggle="tooltip" data-placement="top" title="' . $cli->getCommandAsString('singlesync', $value['ordernumber']) . '">' . __('articlestatus_sync') . '</button>' . PHP_EOL;
            $table .= '                </form>' . PHP_EOL;
            $table .= '            </td>' . PHP_EOL;
            $table .= '        </tr>' . PHP_EOL;
        }
        $table .= '    </tbody>' . PHP_EOL;
        $table .= '</table>' . PHP_EOL;

        return $table;
    }

    /**
     * renderBtnSyncThemAll.
     *
     * @return string
     */
    private function renderBtnSyncThemAll()
    {
        $btn = '<form action="{{formaction}}" method="post">' . PHP_EOL;
        $btn .= '    <input type="hidden" name="formname" value="clicommand">' . PHP_EOL;
        $btn .= '    <input type="hidden" name="formkey" value="{{formkey}}">' . PHP_EOL;
        $btn .= '    <input type="hidden" name="formsecret" value="">' . PHP_EOL;
        $btn .= '    <input type="hidden" name="command" value="singlesync">' . PHP_EOL;
        $btn .= '    <input type="hidden" name="option_all" value="0">' . PHP_EOL;
        $btn .= '    <input type="hidden" name="option_vvv" value="1">' . PHP_EOL;
        $btn .= '    <input type="hidden" name="option_backlog" value="1">' . PHP_EOL;
        $btn .= '    <input type="hidden" name="product" value="' . implode(',', $this->productList) . '">' . PHP_EOL;
        $btn .= '    <input type="hidden" name="type" value="hide">' . PHP_EOL;
        $btn .= '    <button class="btn btn-psc7" type="submit" data-toggle="tooltip" data-placement="top" title="Das kann dauern....">' . __('articlestatus_syncthemall') . '</button>' . PHP_EOL;
        $btn .= '</form>' . PHP_EOL;

        return $btn;
    }
}
