<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Session;

use psc7helper\App\Models\Model_Abstract;
use psc7helper\App\Models\Model_Interface;

class Model extends Model_Abstract implements Model_Interface {

    /**
     * validateUserID
     * @param string $userID
     * @return boolean
     */
    public function validateUserID($userID) {
        $data = $this->database->selectVar("
            SELECT
                count(*)
            FROM
                `PREFIX_s_core_auth`
            WHERE
                `id` = ?
                AND `active`= ?
                AND `failedlogins` < ?
            ", array(
                $userID,
                1,
                3
            )
        );
        if ($data == 1) {
            return true;
        }
        return false;
    }

    /**
     * validateUsername
     * @param string $username
     * @return boolean
     */
    public function validateUsername($username) {
        $data = $this->database->selectVar("
            SELECT
                count(*)
            FROM
                `PREFIX_s_core_auth`
            WHERE
                `username` = ?
                AND `active` = 1
                AND `failedlogins` <= 3
            ", array(
                $username
            )
        );
        if ($data == 1) {
            return true;
        }
        return false;
    }

    /**
     * getPasswordHash
     * @param string $username
     * @return boolean
     */
    public function getPasswordHash($username) {
        $data = $this->database->selectVar("
            SELECT
                `password`
            FROM
                `PREFIX_s_core_auth`
            WHERE
                `username` = ?
                AND `active` = 1
                AND `failedlogins` <= 3
            ", array(
                $username
            )
        );
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * getUserID
     * @param string $username
     * @param string $hash
     * @return boolean
     */
    public function getUserID($username, $hash) {
        $data = $this->database->selectVar("
            SELECT
                `id`
            FROM
                `PREFIX_s_core_auth`
            WHERE
                `username` = ?
                AND `password` = ?
                AND `active` = 1
                AND `failedlogins` <= 3
            ", array(
                $username,
                $hash
            )
        );
        if ($data) {
            return $data;
        }
        return false;
    }

}
