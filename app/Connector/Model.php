<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Connector;

use psc7helper\App\Models\Model_Abstract;
use psc7helper\App\Models\Model_Interface;

class Model extends Model_Abstract implements Model_Interface
{
    /**
     * getBacklogCount.
     *
     * @return int
     */
    public function getBacklogCount()
    {
        $data = $this->database->selectVar('
            SELECT
                count(*) as count
            FROM
                `PREFIX_plenty_backlog`
        ');
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * countByObjectType.
     *
     * @param string $objectType
     * @param string $adapterName
     *
     * @return int
     */
    public function countByObjectType($objectType, $adapterName)
    {
        $data = $this->database->selectVar('
            SELECT
                count(*) as count
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `objectType` = ?
                AND `adapterName` = ?
        ', [
            (string) $objectType,
            (string) $adapterName,
        ]);
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * findObjectIdentifierByReference.
     *
     * @param string $reference
     *
     * @return string
     */
    public function findObjectIdentifierByReference($reference)
    {
        if (! is_string($reference)) {
            return false;
        }
        $articleID = $this->database->selectVar(
            '
            SELECT
                `articleID`
            FROM
                `PREFIX_s_articles_details`
            WHERE
                `ordernumber` = ?
            LIMIT
                1
            ',
            [
                $reference,
            ]
        );
        if (! $articleID) {
            return false;
        }
        $objectIdentifier = $this->database->selectVar(
            "
            SELECT
                `objectIdentifier`
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `adapterIdentifier` = ?
                AND (`objectType` = 'Product' or `objectType` = 'Variation')
            LIMIT
                1
            ",
            [
                $articleID,
            ]
        );
        if ($objectIdentifier) {
            return $objectIdentifier;
        }

        return false;
    }

    /**
     * getProductlistAsArray.
     *
     * @return array
     */
    public function getProductlistAsArray()
    {
        $data = $this->database->selectAssoc('
            SELECT DISTINCT
                `ordernumber`
            FROM
                `PREFIX_s_articles_details`
        ');
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * findIdentity.
     *
     * @param string $search
     * @param string $column
     *
     * @return string
     */
    public function findIdentity($search, $column)
    {
        if ('objectIdentifier' == $column) {
            $data = $this->database->selectAssoc('
                SELECT
                    *
                FROM
                    `PREFIX_plenty_identity`
                WHERE
                    `objectIdentifier` = ?
                LIMIT
                    100
            ', [
                (string) $search,
            ]);
        }
        if ('adapterIdentifier' == $column) {
            $data = $this->database->selectAssoc('
                SELECT
                    *
                FROM
                    `PREFIX_plenty_identity`
                WHERE
                    `adapterIdentifier` = ?
                LIMIT
                    100
            ', [
                (string) $search,
            ]);
        }
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * getCronjobList.
     *
     * @return array
     */
    public function getCronjobList()
    {
        $data = $this->database->selectAssoc("
            SELECT
                *
            FROM
                `PREFIX_s_crontab`
            WHERE
                `name` LIKE 'PlentyConnector%'
        ");
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * getCronjobByName.
     *
     * @param string $name
     *
     * @return array
     */
    public function getCronjobByName($name)
    {
        $data = $this->database->selectAssoc(
            '
            SELECT
                *
            FROM
                `PREFIX_s_crontab`
            WHERE
                `name` = ?
            LIMIT
                1
            ',
            [
                $name,
            ]
        );
        if ($data) {
            return $data[0];
        }

        return false;
    }

    /**
     * getCronjobById.
     *
     * @param int $id
     *
     * @return array
     */
    public function getCronjobById($id)
    {
        $data = $this->database->selectAssoc(
            '
            SELECT
                *
            FROM
                `PREFIX_s_crontab`
            WHERE
                `id` = ?
            LIMIT
                1
            ',
            [
                (int) $id,
            ]
        );
        if ($data) {
            return $data[0];
        }

        return false;
    }

    /**
     * updateCronjob.
     *
     * @param int    $id
     * @param string $next
     * @param int    $interval
     * @param int    $disable_on_error
     * @param type   $inform_mail
     *
     * @return array
     */
    public function updateCronjob($id, $next, $interval, $disable_on_error, $inform_mail)
    {
        $cronjob = $this->getCronjobById((int) $id);
        if (! $cronjob && 0 == $cronjob['active']) {
            return false;
        }
        $timeForNull = time() - 5200;
        if ('' == $next) {
            $next = date('Y-m-d H:i:s', $timeForNull);
        }
        $filds = [
            'next'             => $next,
            'interval'         => $interval,
            'disable_on_error' => $disable_on_error,
            'inform_mail'      => $inform_mail,
        ];
        if (null == $cronjob['start']) {
            $filds['start'] = date('Y-m-d H:i:s', $timeForNull);
        }
        if (null == $cronjob['end']) {
            $filds['end'] = date('Y-m-d H:i:s', $timeForNull);
        }
        $this->database->update('s_crontab', $filds, ['id' => (int) $id]);

        return true;
    }

    /**
     * deactivateCronjob.
     *
     * @param int $id
     *
     * @return bool
     */
    public function deactivateCronjob($id)
    {
        $cronjob = $this->getCronjobById((int) $id);
        if (! $cronjob && 1 == $cronjob['active']) {
            return false;
        }
        $this->database->update(
            's_crontab',
            [
                'active' => '0',
            ],
            [
                'id' => (int) $id, ]
            );

        return true;
    }

    /**
     * deactivateAllCronjobs.
     *
     * @return bool
     */
    public function deactivateAllCronjobs()
    {
        $this->database->update('s_crontab', ['active' => '0'], ['name' => 'PlentyConnector Synchronize']);
        $this->database->update('s_crontab', ['active' => '0'], ['name' => 'PlentyConnector ProcessBacklog']);
        $this->database->update('s_crontab', ['active' => '0'], ['name' => 'PlentyConnector Cleanup']);

        return true;
    }

    /**
     * activateCronjob.
     *
     * @param int $id
     *
     * @return bool
     */
    public function activateCronjob($id)
    {
        $cronjob = $this->getCronjobById((int) $id);
        if (! $cronjob && 0 == $cronjob['active']) {
            return false;
        }
        $this->database->update(
            's_crontab',
            [
                'active' => '1',
            ],
            [
                'id' => (int) $id, ]
            );

        return true;
    }

    /**
     * reactivateAllCronjobs.
     *
     * @return bool
     */
    public function reactivateAllCronjobs()
    {
        $this->database->update('s_crontab', ['active' => '1'], ['name' => 'PlentyConnector Synchronize']);
        $this->database->update('s_crontab', ['active' => '1'], ['name' => 'PlentyConnector ProcessBacklog']);
        $this->database->update('s_crontab', ['active' => '1'], ['name' => 'PlentyConnector Cleanup']);

        return true;
    }
}
