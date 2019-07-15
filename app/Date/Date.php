<?php

/**
 * This file is part of the michael-rusch/proclaim.
 *
 * This software is proprietary
 * Unauthorized copying of this file is strictly prohibited
 *
 * For the full copyright and license information, please view the LICENSE file
 *
 * @author Michael Rusch <michael@rusch.sh>
 * @copyright Copyright (c) 2018 Michael Rusch
 *
 * @see https://www.rusch.sh
 */

namespace psc7helper\App\Date;

use psc7helper\App\Config\Config;

class Date
{
    /**
     * instance.
     *
     * @var object
     */
    private static $instance;

    /**
     * __construct.
     */
    private function __construct()
    {
        ini_set('date.timezone', Config::get('dateTimezone'));
        date_default_timezone_set(Config::get('dateTimezone'));
    }

    /**
     * __clone.
     */
    private function __clone()
    {
    }

    /**
     * getInstance.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * init.
     *
     * @return self
     */
    public static function init()
    {
        return self::getInstance();
    }

    /**
     * getTime.
     *
     * @return int
     */
    public static function getTime()
    {
        return time();
    }

    /**
     * datetimeToTimestamp.
     *
     * @param string $datetime
     *
     * @return int
     */
    public static function datetimeToTimestamp($datetime)
    {
        return strtotime($datetime);
    }

    /**
     * getDate.
     *
     * @param string $format
     * @param int    $timestamp
     *
     * @return string
     */
    public static function getDate($format = 'd-m-Y H:i:s', $timestamp = 0)
    {
        if (! $format || ! is_string($format)) {
            $format = Config::get('dateFormat');
        }
        if ($timestamp > 0) {
            return date($format, $timestamp);
        } else {
            return date($format, time());
        }
    }

    /**
     * getDayName.
     *
     * @param int  $day
     * @param bool $short
     *
     * @return string
     */
    public static function getDayName($day, $short = false)
    {
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
     * dayName.
     *
     * @param int  $num
     * @param bool $short
     *
     * @return string
     */
    private static function dayName($num, $short = false)
    {
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
     * getMonthName.
     *
     * @param int  $month
     * @param bool $short
     *
     * @return string
     */
    public static function getMonth($month, $short = false)
    {
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
     * monthName.
     *
     * @param int  $num
     * @param bool $short
     *
     * @return string
     */
    private static function monthName($num, $short = false)
    {
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

    /**
     * isWeekend.
     *
     * @param int $timestamp
     *
     * @return bool
     */
    public static function isWeekend($timestamp)
    {
        $numberOfDay = date('N', $timestamp);
        if (6 == $numberOfDay || 7 == $numberOfDay) {
            return true;
        }

        return false;
    }

    /**
     * isHoliday.
     *
     * @param int $timestamp
     *
     * @return bool
     */
    public static function isHoliday($timestamp)
    {
        $year = date('Y', $timestamp);
        $easterSunday = easter_date($year);
        $easterMonday = $easterSunday + 86400;
        $goodFriday = $easterSunday - (86400 * 2);
        $ascensionOfChrist = $easterSunday + (86400 * 39);
        $pentecostSunday = $easterSunday + (86400 * 49);
        $pentecostMonday = $pentecostSunday + 86400;
        $corpusChristi = $easterMonday + (86400 * 59);
        $holidays = [
            ['name' => 'Neujahr',                   'date' => '01.01.',                         'state' => 'alle'],
            ['name' => 'Heilige Drei Könige',       'date' => '06.01.',                         'state' => 'BW, BE, ST'],
            ['name' => 'Internationaler Frauentag', 'date' => '08.03.',                         'state' => 'BE'],
            ['name' => 'Karfreitag (Ostern)',       'date' => date('d.m.', $goodFriday),        'state' => 'alle'],
            ['name' => 'Ostersonntag (Ostern)',     'date' => date('d.m.', $easterSunday),      'state' => 'BB'],
            ['name' => 'Ostermontag',               'date' => date('d.m.', $easterMonday),      'state' => 'alle'],
            ['name' => 'Tag der Arbeit',            'date' => '01.05.',                         'state' => 'alle'],
            ['name' => 'Christi Himmelfahrt',       'date' => date('d.m.', $ascensionOfChrist), 'state' => 'alle'],
            ['name' => 'Pfingstsonntag',            'date' => date('d.m.', $pentecostSunday),   'state' => 'BB'],
            ['name' => 'Pfingstmontag',             'date' => date('d.m.', $pentecostMonday),   'state' => 'alle'],
            ['name' => 'Fronleichnam',              'date' => date('d.m.', $corpusChristi),     'state' => 'BW, BY, HE, NW, RP, SL'],
            ['name' => 'Augsburger Friedensfest',   'date' => '08.08.',                         'state' => 'BY'],
            ['name' => 'Mariä Himmelfahrt',         'date' => '15.08.',                         'state' => 'BY, SL'],
            ['name' => 'Tag der Deutschen Einheit', 'date' => '03.10.',                         'state' => 'alle'],
            ['name' => 'Reformationstag',           'date' => '31.10.',                         'state' => 'BB, HB, HH, MV, NI, SN, ST, SH, TH'],
            ['name' => 'Allerheiligen',             'date' => '01.11.',                         'state' => 'BW, BE, NW, SL'],
            ['name' => 'Buß- und Bettag',           'date' => '20.11.',                         'state' => 'SN'],
            ['name' => '1. Weihnachtstag',          'date' => '25.12.',                         'state' => 'alle'],
            ['name' => '2. Weihnachtstag',          'date' => '26.12.',                         'state' => 'alle'],
        ];
        $isHolyday = false;
        foreach ($holidays as $value) {
            $date = date('d.m.', $timestamp);
            if ($date == $value['date'] && 'alle' == $value['state']) {
                $isHolyday = true;
            }
        }

        return $isHolyday;
    }
}
