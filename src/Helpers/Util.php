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
class Util
{

    /**
     * create a unique number and return it
     *
     * @return string 반환
     */
    public static function getUniqueId() : string
    {
        return sha1(uniqid(getmypid() . rand(), true));
    }

    /**
     * delete local file and return bool type
     *
     * @param string $path file's full path
     * @param string $fileName file's full name
     * @return bool
     */
    public static function localDelete(string $path, string  $fileName) : bool
    {
        if (is_file($path . $fileName)) {
            unlink($path . $fileName);
        } else {
            return false;
        }
        return true;
    }

    /**
     * create uuid and return it
     *
     * @return string
     */
    public static function getUUID() : string
    {
        mt_srand((double) microtime() * 10000);
        $charId = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        return substr($charId, 0, 8) . $hyphen . substr($charId, 8, 4) . $hyphen . substr($charId, 12, 4) . $hyphen . substr($charId, 16, 4) . $hyphen . substr($charId, 20, 12);
    }


    /**
     * create random string and return it
     *
     * @param int $length
     * @return string
     */
    public static function getRandomString(int $length = 64) : string
    {
        $characters = "01234567890123456789";
        $characters .= "abcdefghijklmnopqrstuvwxyz";

        $string_generated = "";

        while ($length--) {
            $string_generated .= $characters[mt_rand(0, 45)];
        }

        return $string_generated;
    }

}