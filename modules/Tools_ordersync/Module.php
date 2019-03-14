<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Tools_ordersync;

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
        $this->setPlaceholder('cardtitle', __('ordersync_cardtitle'), false);
        $cli = $this->cli;
        $list = $this->getList(50);
        $table = '<table class="table table-striped">' . PHP_EOL;
        $table .= '    <thead>' . PHP_EOL;
        $table .= '        <tr>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col">' . __('ordersync_sordernumber') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left d-none d-lg-table-cell" scope="col">' . __('ordersync_sordertime') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left d-none d-md-table-cell" scope="col">' . __('ordersync_sorderinfo') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col">' . __('ordersync_porderid') . '</th>' . PHP_EOL;
        $table .= '            <th class="text-left" scope="col"></th>' . PHP_EOL;
        $table .= '        </tr>' . PHP_EOL;
        $table .= '    </thead>' . PHP_EOL;
        $table .= '    <tbody>' . PHP_EOL;
        foreach ($list as $value) {
            $table .= '        <tr>' . PHP_EOL;
            $table .= '            <td class="text-left">' . $value['sordernumber'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left d-none d-lg-table-cell">' . $value['sordertime'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left d-none d-md-table-cell">' . $value['sorderinfo'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left">' . $value['porderid'] . '</td>' . PHP_EOL;
            $table .= '            <td class="text-left">' . PHP_EOL;
            $table .= '                <form action="{{formaction}}" method="post">' . PHP_EOL;
            $table .= '                    <input type="hidden" name="formname" value="clicommand">' . PHP_EOL;
            $table .= '                    <input type="hidden" name="formkey" value="{{formkey}}">' . PHP_EOL;
            $table .= '                    <input type="hidden" name="formsecret" value="">' . PHP_EOL;
            $table .= '                    <input type="hidden" name="command" value="singlesync_order">' . PHP_EOL;
            $table .= '                    <input type="hidden" name="option_all" value="0">' . PHP_EOL;
            $table .= '                    <input type="hidden" name="option_vvv" value="1">' . PHP_EOL;
            $table .= '                    <input type="hidden" name="option_backlog" value="1">' . PHP_EOL;
            $table .= '                    <input type="hidden" name="product" value="' . $value['objectIdentifier'] . '">' . PHP_EOL;
            $table .= '                    <input type="hidden" name="type" value="hide">' . PHP_EOL;
            $table .= '                    <button class="btn btn-psc7" type="submit" data-toggle="tooltip" data-placement="top" title="' . $cli->getCommandAsString('singlesync_order', $value['objectIdentifier']) . '">' . __('ordersync_sync') . '</button>' . PHP_EOL;
            $table .= '                </form>' . PHP_EOL;
            $table .= '            </td>' . PHP_EOL;
            $table .= '        </tr>' . PHP_EOL;
        }
        $table .= '    </tbody>' . PHP_EOL;
        $table .= '</table>' . PHP_EOL;
        $this->setPlaceholder('table', $table, false);
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
        $orders = $model->getOrders((int) $number);
        foreach ($orders as $value) {
            $date = strtotime($value['ordertime']);
            $objectidentifier = $this->getObjectIdentifier($value['id'], 'Shopware');
            $list[] = [
                'sordernumber'     => $value['ordernumber'],
                'sordertime'       => date('d.m.Y H:i:s', $date),
                'sorderinfo'       => $this->getOrderinfo($value['id']),
                'porderid'         => $this->getPlentyOrderid($objectidentifier),
                'objectIdentifier' => $objectidentifier,
            ];
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
     * getOrderinfo.
     *
     * @param int $orderid
     *
     * @return string
     */
    private function getOrderinfo($orderid)
    {
        $model = $this->model;

        return $model->getOrderinfo((int) $orderid);
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
}
