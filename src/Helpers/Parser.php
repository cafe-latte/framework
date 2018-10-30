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

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class Parser
{

    /**
     * ini parser
     *
     * @param $filename
     * @param bool $process_sections
     * @return array
     */
    public static function iniToArray($filename, $process_sections = true): array
    {
        $ini = parse_ini_file($filename, $process_sections);
        return (array)$ini;
    }

    /**
     * To convert json tp array
     *
     * @param $filename
     * @return array
     */
    public static function jsonToArray($filename): array
    {
        return json_decode(file_get_contents($filename), true);
    }

    /**
     * To convert array to json
     *
     * @param $value
     * @return array
     */
    public static function arrayToJson(array $value): array
    {
        return json_encode($value);
    }

    /**
     * convert data from array to xml
     *
     * @param $arr
     * @param string $num_prefix
     * @return string
     */
    public static function arrayToXml($arr, $num_prefix = "num_") : string
    {
        if (!is_array($arr)) return $arr;
        $result = '';
        foreach ($arr as $key => $val) {
            $key = (is_numeric($key) ? $num_prefix . $key : $key);

            $result .= '<' . $key . '>' . self::arrayToXml($val, $num_prefix) . '</' . $key . '>';
        }
        return $result;
    }
}