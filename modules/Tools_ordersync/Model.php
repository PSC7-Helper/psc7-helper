<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Tools_ordersync;

use psc7helper\App\Models\Model_Abstract;
use psc7helper\App\Models\Model_Interface;

class Model extends Model_Abstract implements Model_Interface {

    /**
     * getOrders
     * @param integer $orders
     * @return array
     */
    public function getOrders($orders) {
        $data = $this->database->selectAssoc("
            SELECT
                *
            FROM
                `PREFIX_s_order`
            WHERE
                `ordernumber` NOT LIKE '0'
            ORDER BY
                `id` DESC
            LIMIT
                ?
            ", array(
                (int) $orders
            )
        );
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * getObjectIdentifier
     * @param string $adapterIdentifier
     * @param string $adapterName
     * @return string
     */
    public function getObjectIdentifier($adapterIdentifier, $adapterName) {
        $adapter = 'ShopwareAdapter';
        switch ($adapterName) {
            case 'Plenty':
                $adapter = 'PlentymarketsAdapter';
                break;
            case 'Shopware':
                $adapter = 'ShopwareAdapter';
                break;
        }
        $data = $this->database->selectVar("
            SELECT
                `objectIdentifier`
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `adapterIdentifier`= ?
                AND `adapterName` = ?
                AND `objectType` = 'Order'
            LIMIT
                1
            ", array(
                $adapterIdentifier,
                $adapter
            )
        );
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * getOrderinfo
     * @param integer $orderid
     * @return string
     */
    public function getOrderinfo($orderid) {
        $data = $this->database->selectVar("
            SELECT
                concat(`firstname`, ' ', `lastname`)
            FROM
                `PREFIX_s_order_billingaddress`
            WHERE
                `orderID`= ?
            LIMIT
                1
            ", array(
                $orderid
            )
        );
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * getPlentyOrderid
     * @param string $objectidentifier
     * @return string
     */
    public function getPlentyOrderid($objectidentifier) {
        $data = $this->database->selectVar("
            SELECT
                `adapterIdentifier`
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `objectIdentifier`= ?
                AND `adapterName` = 'PlentymarketsAdapter'
                AND `objectType` = 'Order'
            LIMIT
                1
            ", array(
                $objectidentifier
            )
        );
        if ($data) {
            return $data;
        }
        return false;
    }

}
