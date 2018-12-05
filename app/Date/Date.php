<?php

/**
 * This file is part of the michael-rusch/proclaim
 * 
 * This software is proprietary
 * Unauthorized copying of this file is strictly prohibited
 * 
 * For the full copyright and license information, please view the LICENSE file
 * 
 * @author Michael Rusch <michael@rusch.sh>
 * @copyright Copyright (c) 2018 Michael Rusch
 * @link https://www.rusch.sh
 */

namespace psc7helper\App\Date;

use psc7helper\App\Config\Config;

class Date {

    /**
     * instance
     * @var object
     */
    private static $instance;

    /**
     * __construct
     */
    private function __construct() {
        ini_set('date.timezone', Config::get('dateTimezone'));
        date_default_timezone_set(Config::get('dateTimezone'));
    }

    /**
     * __clone
     */
    final private function __clone() {
        
    }

    /**
     * getInstance
     * @return self
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * init
     * @return self
     */
    public static function init() {
        return self::getInstance();
    }

    /**
     * getTime
     * @return integer
     */
    public static function getTime() {
        return time();
    }

    /**
     * datetimeToTimestamp
     * @param string $datetime
     * @return integer
     */
    public static function datetimeToTimestamp($datetime) {
        return strtotime($datetime);
    }

    /**
     * getDate
     * @param string $format
     * @param int $timestamp
     * @return string
     */
    public static function getDate($format = 'd-m-Y H:i:s', $timestamp = 0) {
        if (!$format || !is_string($format)) {
            $format = Config::get('dateFormat');
        }
        if ($timestamp > 0) {
            return date($format, $timestamp);
        } else {
            return date($format, time());
        }
    }

    /**
     * getDayName
     * @param int $day
     * @param bool $short
     * @return string
     */
    public static function getDayName($day, $short = false) {
        if ($short) {
            $short = true;
        } else {
            $short = false;
        }
        if ($day) {
            return self::dayName($day, $short);
        }
        return self::dayName(date('w'), $short);
    }

    /**
     * dayName
     * @param int $num
     * @param bool $short
     * @return string
     */
    private static function dayName($num, $short = false) {
        $day = false;
        switch ($num) {
            case 0:
                $day = __('date_sunday');
                break;
            case 1:
                $day = __('date_monday');
                break;
            case 2:
                $day = __('date_tuesday');
                break;
            case 3:
                $day = __('date_wednesday');
                break;
            case 4:
                $day = __('date_thursday');
                break;
            case 5:
                $day = __('date_friday');
                break;
            case 6:
                $day = __('date_saturday');
                break;
            default:
                $day = false;
                break;
        }
        if ($short) {
            $day = substr($day, 0, 2) . '.';
        }
        return $day;
    }

    /**
     * getMonthName
     * @param int $month
     * @param bool $short
     * @return string
     */
    public static function getMonth($month, $short = false) {
        if ($short) {
            $short = true;
        } else {
            $short = false;
        }
        if ($month) {
            return self::monthName($month, $short);
        }
        return self::monthName(date('n'), $short);
    }

    /**
     * monthName
     * @param int $num
     * @param bool $short
     * @return string
     */
    private static function monthName($num, $short = false) {
        $month = false;
        switch ($num) {
            case 1:
                $month = __('date_january');
                break;
            case 2:
                $month = __('date_february');
                break;
            case 3:
                $month = __('date_march');
                break;
            case 4:
                $month = __('date_april');
                break;
            case 5:
                $month = __('date_may');
                break;
            case 6:
                $month = __('date_june');
                break;
            case 7:
                $month = __('date_july');
                break;
            case 8:
                $month = __('date_august');
                break;
            case 9:
                $month = __('date_september');
                break;
            case 10:
                $month = __('date_october');
                break;
            case 11:
                $month = __('date_november');
                break;
            case 12:
                $month = __('date_december');
                break;
        }
        if ($short) {
            $month = substr($month, 0, 3) . '.';
        }
        return $month;
    }

}
