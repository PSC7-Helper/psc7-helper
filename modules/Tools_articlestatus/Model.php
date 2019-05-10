<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\Module\Tools_articlestatus;

use psc7helper\App\Models\Model_Abstract;
use psc7helper\App\Models\Model_Interface;

class Model extends Model_Abstract implements Model_Interface
{
    /**
     * getArticles.
     *
     * @param int $limit
     *
     * @return array
     */
    public function getArticles($limit)
    {
        $data = $this->database->selectAssoc(
            '
            SELECT
                a.`id`,
                a.`name`,
                ad.`ordernumber`,
                a.`active` AS a_active,
                ad.`active` AS ad_active
            FROM
                `PREFIX_s_articles` AS a
            INNER JOIN
                `PREFIX_s_articles_details` AS ad
            ON
                a.`id` = ad.`articleID`
            WHERE
                a.`active` != ad.`active`
            ORDER BY
                ad.`ordernumber` ASC
            LIMIT
                ?
            ',
            [
                (int) $limit,
            ]
        );
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * getObjectIdentifier.
     *
     * @param string $adapterIdentifier
     * @param string $adapterName
     *
     * @return string
     */
    public function getObjectIdentifier($adapterIdentifier, $adapterName)
    {
        $adapter = 'ShopwareAdapter';
        switch ($adapterName) {
            case 'Plenty':
                $adapter = 'PlentymarketsAdapter';
                break;
            case 'Shopware':
                $adapter = 'ShopwareAdapter';
                break;
        }
        $data = $this->database->selectVar(
            "
            SELECT
                `objectIdentifier`
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `adapterIdentifier`= ?
                AND `adapterName` = ?
                AND `objectType` = 'Product'
            LIMIT
                1
            ",
            [
                $adapterIdentifier,
                $adapter,
            ]
        );
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * getPlentyOrderid.
     *
     * @param string $objectidentifier
     *
     * @return string
     */
    public function getPlentyOrderid($objectidentifier)
    {
        $data = $this->database->selectVar(
            "
            SELECT
                `adapterIdentifier`
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `objectIdentifier`= ?
                AND `adapterName` = 'PlentymarketsAdapter'
                AND `objectType` = 'Product'
            LIMIT
                1
            ",
            [
                $objectidentifier,
            ]
        );
        if ($data) {
            return $data;
        }

        return false;
    }
}
