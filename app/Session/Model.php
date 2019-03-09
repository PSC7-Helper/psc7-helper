<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Session;

use psc7helper\App\Models\Model_Abstract;
use psc7helper\App\Models\Model_Interface;

class Model extends Model_Abstract implements Model_Interface
{
    /**
     * validateUserID.
     *
     * @param string $userID
     *
     * @return bool
     */
    public function validateUserID($userID)
    {
        $data = $this->database->selectVar(
            '
            SELECT
                count(*)
            FROM
                `PREFIX_s_core_auth`
            WHERE
                `id` = ?
                AND `active`= ?
                AND `failedlogins` < ?
            ',
            [
                $userID,
                1,
                3,
            ]
        );
        if (1 == $data) {
            return true;
        }

        return false;
    }

    /**
     * validateUsername.
     *
     * @param string $username
     *
     * @return bool
     */
    public function validateUsername($username)
    {
        $data = $this->database->selectVar(
            '
            SELECT
                count(*)
            FROM
                `PREFIX_s_core_auth`
            WHERE
                `username` = ?
                AND `active` = 1
                AND `failedlogins` <= 3
            ',
            [
                $username,
            ]
        );
        if (1 == $data) {
            return true;
        }

        return false;
    }

    /**
     * getPasswordHash.
     *
     * @param string $username
     *
     * @return bool
     */
    public function getPasswordHash($username)
    {
        $data = $this->database->selectVar(
            '
            SELECT
                `password`
            FROM
                `PREFIX_s_core_auth`
            WHERE
                `username` = ?
                AND `active` = 1
                AND `failedlogins` <= 3
            ',
            [
                $username,
            ]
        );
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * getUserID.
     *
     * @param string $username
     * @param string $hash
     *
     * @return bool
     */
    public function getUserID($username, $hash)
    {
        $data = $this->database->selectVar(
            '
            SELECT
                `id`
            FROM
                `PREFIX_s_core_auth`
            WHERE
                `username` = ?
                AND `password` = ?
                AND `active` = 1
                AND `failedlogins` <= 3
            ',
            [
                $username,
                $hash,
            ]
        );
        if ($data) {
            return $data;
        }

        return false;
    }

    /**
     * setFailedlogin.
     *
     * @param string $username
     *
     * @return bool
     */
    public function setFailedlogin($username)
    {
        $failed = $this->failedlogins($username);
        $untiltime = time() + (30 * $failed);
        $untildate = date('Y-m-d H:i:s', $untiltime);
        $this->database->update('s_core_auth', ['failedlogins' => $failed + 1, 'lockeduntil' => $untildate], ['username' => $username]);

        return true;
    }

    /**
     * failedlogins.
     *
     * @param string $username
     *
     * @return bool
     */
    public function failedlogins($username)
    {
        $data = $this->database->selectVar(
            '
            SELECT
                `failedlogins`
            FROM
                `PREFIX_s_core_auth`
            WHERE
                `username` = ?
            ',
            [
                $username,
            ]
        );
        if ($data) {
            return $data;
        }

        return false;
    }
}
