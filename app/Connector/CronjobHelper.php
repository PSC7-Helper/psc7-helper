<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Connector;

class CronjobHelper
{
    /**
     * SYNCHRONIZE_MAX.
     */
    const SYNCHRONIZE_MAX = 3600;

    /**
     * BACKLOG_MAX.
     */
    const BACKLOG_MAX = 1800;

    /**
     * CLEANUP_MAX.
     */
    const CLEANUP_MAX = 90000;

    /**
     * model.
     *
     * @var object
     */
    private $model;

    /**
     * __construct.
     */
    public function __construct()
    {
        $this->model = new Model();
    }

    /**
     * getCronjobList.
     *
     * @return array
     */
    public function getCronjobList()
    {
        $cronjobs = $this->model->getCronjobList();

        return $cronjobs;
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
        $cronjob = $this->model->getCronjobByName($name);

        return $cronjob;
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
        $cronjob = $this->model->getCronjobById((int) $id);

        return $cronjob;
    }

    /**
     * getStatus.
     *
     * @param string $name
     * @param int    $active
     * @param int    $nextTs
     *
     * @return int
     */
    public function getStatus($name, $active, $nextTs)
    {
        $status = 1;
        if (! $active) {
            $status = 0;

            return $status;
        }
        $now = time();
        $synchronizeMax = self::SYNCHRONIZE_MAX;
        $backlogMax = self::BACKLOG_MAX;
        $cleanupMax = self::CLEANUP_MAX;
        $difference = ($now - $nextTs);
        if ('Synchronize' == $name && $difference > $synchronizeMax) {
            $status = 2;

            return $status;
        }
        if ('ProcessBacklog' == $name && $difference > $backlogMax) {
            $status = 2;

            return $status;
        }
        if ('Cleanup' == $name && $difference > $cleanupMax) {
            $status = 2;

            return $status;
        }

        return $status;
    }

    /**
     * getTimeDeviation.
     *
     * @param string $dateTime
     *
     * @return type
     */
    public function getTimeDeviation($dateTime)
    {
        $now = time();
        $timestamp = strtotime($dateTime);
        $difference = ($now - $timestamp);

        return $difference;
    }

    /**
     * updateCronjob.
     *
     * @param int    $id
     * @param int    $next
     * @param int    $interval
     * @param int    $disable_on_error
     * @param string $inform_mail
     *
     * @return type
     */
    public function updateCronjob($id, $next, $interval, $disable_on_error, $inform_mail)
    {
        return $this->model->updateCronjob($id, $next, $interval, $disable_on_error, $inform_mail);
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
        return $this->model->deactivateCronjob($id);
    }

    /**
     * deactivateAllCronjobs.
     *
     * @return bool
     */
    public function deactivateAllCronjobs()
    {
        return $this->model->deactivateAllCronjobs();
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
        return $this->model->activateCronjob($id);
    }

    /**
     * reactivateAllCronjobs.
     *
     * @return bool
     */
    public function reactivateAllCronjobs()
    {
        return $this->model->reactivateAllCronjobs();
    }
}
