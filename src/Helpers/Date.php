<?php
/*
 * This file is part of CafeLatte Framework.
 *
 * (c) Thorpe Lee(Gwangbok Lee) <koangbok@gmail.com>
 *
 * For the full copyright and license information, please view
 * the license that is located at the bottom of this file.
 *
 * @license    MIT License
 */

namespace CafeLatte\Helpers;

use CafeLatte\Exception\InvalidLogicException;

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
Class Date
{

    /**
     * get current time as YYYY-MM-DD HH:ii:ss
     *
     * @return bool|string
     */
    public static function getCurrentTime(): string
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * get this year,month,date and  and return it
     *
     * @return mixed
     */
    public static function getCurrentDate(): string
    {
        return date("Y-m-d");
    }

    /**
     * get this year and month and return it
     *
     * @return string
     */
    public static function getCurrentMonth(): string
    {
        return date("Y-m");
    }

    /**
     * get this year and return it
     *
     * @return string
     */
    public static function getCurrentYear(): string
    {
        return date("Y");
    }

    /**
     * @return string
     */
    public static function getMicroTime()
    {
        $mt = explode(' ', microtime());
        return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
    }



    /**
     * 이전달
     *
     * @param string $date
     * @return string
     */
    public static function getPreviousMonth(string $date): string
    {
        return date("Y-m", strtotime("-1 month", strtotime($date)));
    }

    /**
     * get previous week from current date and return it as YYYY-MM-DD format
     *
     * @param string $date
     * @return string
     */
    public static function getPreviousWeek(string $date): string
    {
        return date("Y-m", strtotime("-7 days", strtotime($date)));
    }

    /**
     * get past date as YYYY-MM-DD
     *
     * @param int $period
     * @return string
     */
    public static function getPastDate(int $period): string
    {
        return date("Y-m-d", strtotime("-{$period} days", strtotime(self::getCurrentDate())));
    }

    /**
     * 다음달
     *
     * @param string $date
     * @return string
     */
    public static function getNextMonth(string $date): string
    {
        return date("Y-m", strtotime("+1 month", strtotime($date)));
    }

    /**
     * 어제 날짜및 시간
     *
     * @return string
     */
    public static function getYesterdayTime(): string
    {
        $datetime = new \DateTime('yesterday');
        return $datetime->format('Y-m-d H:i:s');
    }

    /**
     * 어제 날짜만
     *
     * @return string
     */
    public static function getYesterdayDate(): string
    {
        $datetime = new \DateTime('yesterday');
        return $datetime->format('Y-m-d');
    }

    /**
     * 내일 날짜및 시간
     *
     * @return string
     */
    public static function getTomorrowTime(): string
    {
        $datetime = new \DateTime('tomorrow');
        return $datetime->format('Y-m-d H:i:s');
    }

    /**
     * 내일 날짜
     *
     * @return string
     */
    public static function getTomorrowDate(): string
    {
        $datetime = new \DateTime('tomorrow');
        return $datetime->format('Y-m-d');
    }


    /**
     * 두 날짜의 차이를 날 수로 리턴
     *
     * @param string $sDate 시작날짜
     * @param string $eDate 종료날짜
     * @return string
     */
    public static function getDiffDay(string $sDate, string $eDate)
    {
        $sDate = new \DateTime($sDate);
        $interval = $sDate->diff(new \DateTime($eDate));
        return $interval->format('%a');
    }

    /**
     * 입력시간과 초를 기준(더하기, 빼기)으로 일자및 시간 구한다.
     *
     * @param string $time 입력일시및 시간
     * @param int $sec 초
     * @return string
     */
    public static function getTime(string $time, int $sec): string
    {
        $date = new \DateTime($time);
        $date->modify("{$sec} sec");
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * 입력시간과 초를 기준(더하기, 빼기)으로 일자
     *
     * @param string $time 입력일시및 시간
     * @param int $sec 초
     * @return string
     */
    public static function getDate(string $time, int $sec): string
    {
        $date = new \DateTime($time);
        $date->modify("{$sec} sec");
        return $date->format('Y-m-d');
    }

    /**
     * 매월 말일 계산하기
     *
     * @param int $month
     * @param string $year
     * @return string
     */
    public static function getLastDay(int $month, string $year)
    {
        return strftime("%d", mktime(0, 0, 0, $month + 1, 0, $year));
    }

    /**
     * @param string $second
     * @return string
     */
    public static function getHHIISSBySecond(string $second)
    {
        return gmdate('H:i:s', $second);
    }

    /**
     * 두 기간 사이에 타에 시간 차이를 구한다.
     *
     * @param string $time01 과거 시간
     * @param string $time02 미래 시간
     * @param string $returnStyle
     * @return int
     */

    public static function getTimeDiff(string $time01, string $time02, string $returnStyle = "s")
    {
        $diff = date_diff(new \DateTime($time01), new \DateTime($time02));
        switch ($returnStyle) {
            case "y":
                $total = $diff->y + $diff->m / 12 + $diff->d / 365.25;
                break;
            case "m":
                $total = $diff->y * 12 + $diff->m + $diff->d / 30 + $diff->h / 24;
                break;
            case "d":
                $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
                break;
            case "h":
                $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i / 60;
                break;
            case "i":
                $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s / 60;
                break;
            case "s":
                $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i) * 60 + $diff->s;
                break;
            default :
                throw new InvalidLogicException("returnStyle 값 미지정");

        }

        return $total;
    }

    /**
     * @return string
     */
    public static function getPreviousMin(): string
    {
        return date("Y-m-d H:i:s", strtotime("-60 min", strtotime(Date::getCurrentTime())));
    }

}
