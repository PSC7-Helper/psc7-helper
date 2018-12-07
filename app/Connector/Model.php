<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Connector;

use psc7helper\App\Models\Model_Abstract;
use psc7helper\App\Models\Model_Interface;

class Model extends Model_Abstract implements Model_Interface {

    /**
     * getBacklogCount
     * @return integer
     */
    public function getBacklogCount() {
        $data = $this->database->selectVar("
            SELECT
                count(*) as count
            FROM
                `PREFIX_plenty_backlog`
        ");
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * countByObjectType
     * @param string $objectType
     * @param string $adapterName
     * @return integer
     */
    public function countByObjectType($objectType, $adapterName) {
        $data = $this->database->selectVar("
            SELECT
                count(*) as count
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `objectType` = ?
                AND `adapterName` = ?
        ", array(
            (string) $objectType,
            (string) $adapterName
        ));
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * findObjectIdentifierByReference
     * @param string $reference
     * @return string
     */
    public function findObjectIdentifierByReference($reference) {
        if (!is_string($reference)) {
            return false;
        }
        $articleID = $this->database->selectVar("
            SELECT
                `articleID`
            FROM
                `PREFIX_s_articles_details`
            WHERE
                `ordernumber` = ?
            LIMIT
                1
            ", array (
                $reference
            )
        );
        if (!$articleID) {
            return false;
        }
        $objectIdentifier = $this->database->selectVar("
            SELECT
                `objectIdentifier`
            FROM
                `PREFIX_plenty_identity`
            WHERE
                `adapterIdentifier` = ?
                AND (`objectType` = 'Product' or `objectType` = 'Variation')
            LIMIT
                1
            ", array (
                $articleID
            )
        );
        if ($objectIdentifier) {
            return $objectIdentifier;
        }
        return false;
    }

    /**
     * getProductlistAsArray
     * @return array
     */
    public function getProductlistAsArray() {
        $data = $this->database->selectAssoc("
            SELECT DISTINCT
                `ordernumber`
            FROM
                `PREFIX_s_articles_details`
        ");
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * findIdentity
     * @param string $search
     * @param string $column
     * @return string
     */
    public function findIdentity($search, $column) {
        if ($column == 'objectIdentifier') {
            $data = $this->database->selectAssoc("
                SELECT
                    *
                FROM
                    `PREFIX_plenty_identity`
                WHERE
                    `objectIdentifier` = ?
                LIMIT
                    100
            ", array(
                (string) $search
            ));
        }
        if ($column == 'adapterIdentifier') {
            $data = $this->database->selectAssoc("
                SELECT
                    *
                FROM
                    `PREFIX_plenty_identity`
                WHERE
                    `adapterIdentifier` = ?
                LIMIT
                    100
            ", array(
                (string) $search
            ));
        }
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * getCronjobList
     * @return array
     */
    public function getCronjobList() {
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
     * getCronjobByName
     * @param string $name
     * @return array
     */
    public function getCronjobByName($name) {
        $data = $this->database->selectAssoc("
            SELECT
                *
            FROM
                `PREFIX_s_crontab`
            WHERE
                `name` = ?
            LIMIT
                1
            ", array (
                $name
            )
        );
        if ($data) {
            return $data[0];
        }
        return false;
    }

    /**
     * getCronjobById
     * @param integer $id
     * @return array
     */
    public function getCronjobById($id) {
        $data = $this->database->selectAssoc("
            SELECT
                *
            FROM
                `PREFIX_s_crontab`
            WHERE
                `id` = ?
            LIMIT
                1
            ", array (
                (int) $id
            )
        );
        if ($data) {
            return $data[0];
        }
        return false;
    }

    /**
     * updateCronjob
     * @param integer $id
     * @param string $next
     * @param integer $interval
     * @param integer $disable_on_error
     * @param type $inform_mail
     * @return array
     */
    public function updateCronjob($id, $next, $interval, $disable_on_error, $inform_mail) {
        $cronjob = $this->getCronjobById((int) $id);
        if (!$cronjob && $cronjob['active'] == 0) {
            return false;
        }
        $timeForNull = time() - 5200;
        if ($next == '') {
            $next = date('Y-m-d H:i:s', $timeForNull);
        }
        $filds = array (
            'next' => $next,
            'interval' => $interval,
            'disable_on_error' => $disable_on_error,
            'inform_mail' => $inform_mail
        );
        if ($cronjob['start'] == null) {
            $filds['start'] = date('Y-m-d H:i:s', $timeForNull);
        }
        if ($cronjob['end'] == null) {
            $filds['end'] = date('Y-m-d H:i:s', $timeForNull);
        }
        $this->database->update('s_crontab', $filds, array('id' => (int) $id));
        return true;
    }

    /**
     * deactivateCronjob
     * @param integer $id
     * @return bool
     */
    public function deactivateCronjob($id) {
        $cronjob = $this->getCronjobById((int) $id);
        if (!$cronjob && $cronjob['active'] == 1) {
            return false;
        }
        $this->database->update(
            's_crontab',
            array(
                'active' => '0'
            ),
            array(
                'id' => (int) $id)
            );
        return true;
    }

    /**
     * deactivateAllCronjobs
     * @return bool
     */
    public function deactivateAllCronjobs() {
        $this->database->update('s_crontab',array('active' => '0'), array('name' => 'PlentyConnector Synchronize'));
        $this->database->update('s_crontab',array('active' => '0'), array('name' => 'PlentyConnector ProcessBacklog'));
        $this->database->update('s_crontab',array('active' => '0'), array('name' => 'PlentyConnector Cleanup'));
        return true;
    }

    /**
     * activateCronjob
     * @param integer $id
     * @return bool
     */
    public function activateCronjob($id) {
        $cronjob = $this->getCronjobById((int) $id);
        if (!$cronjob && $cronjob['active'] == 0) {
            return false;
        }
        $this->database->update(
            's_crontab',
            array(
                'active' => '1'
            ),
            array(
                'id' => (int) $id)
            );
        return true;
    }

    /**
     * reactivateAllCronjobs
     * @return bool
     */
    public function reactivateAllCronjobs() {
        $this->database->update('s_crontab',array('active' => '1'), array('name' => 'PlentyConnector Synchronize'));
        $this->database->update('s_crontab',array('active' => '1'), array('name' => 'PlentyConnector ProcessBacklog'));
        $this->database->update('s_crontab',array('active' => '1'), array('name' => 'PlentyConnector Cleanup'));
        return true;
    }

}
