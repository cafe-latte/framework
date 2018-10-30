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

namespace CafeLatte\Core;

use CafeLatte\Exception\InvalidParameterException;

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class FormValidator
{
    private static $instance = NULL;

    private $inputValue;

    private $inputKey;

    private $rule;

    private $format;

    private $regExes = Array(
        'number' => "^[-]?[0-9,]+\$",
        'string' => "^[\\d\\D]{1,}\$",
    );

    /**
     * @return null|FormValidator
     */
    public static function create()
    {
        if (self::$instance == NULL) {
            self::$instance = new FormValidator();
        }
        return self::$instance;
    }

    /**
     *
     * Validates a single var according to $type.
     * Allows for static calling to allow simple validation.
     *
     * @param string $inputKey
     * @param $inputValue
     * @return $this
     */
    public function setValidate(string $inputKey, $inputValue)
    {
        $this->inputKey = $inputKey;
        $this->inputValue = $inputValue;

        return $this;
    }


    /**
     * if $inputValue is null, put value as default.
     *
     * @param $inputValue
     * @return $this
     */
    public function setDefaultValue($inputValue)
    {
        if (!$this->inputValue) {
            $this->inputValue = $inputValue;
        }

        return $this;
    }


    /**
     *
     *
     * @param $format
     * @return $this
     */
    public function setFormatValue($format)
    {
        if (!$this->inputValue) {
            $this->format = $format;
        }

        return $this;
    }

    /**
     * replace the value
     *
     * @param $key
     * @param string $value
     * @return $this
     */
    public function setReplaceValue($key, string $value)
    {
        if ($this->inputValue) {
            $this->inputValue = str_replace($key, $value, $this->inputValue);
        }

        return $this;
    }


    /**
     * set rule.
     *
     * @param mixed $rule
     * @return $this
     */
    public function setRule($rule)
    {
        $this->rule = $rule;

        return $this;
    }

    /**
     * check min / max length
     *
     * @param int $min
     * @param int $max
     * @return $this
     */
    public function setMinMaxLength(int $min, int $max)
    {
        if ($min > strlen($this->inputValue) or strlen($this->inputValue) > $max) {
            throw new InvalidParameterException($this->inputKey . " : '" . $this->inputValue . "' 의 길이값 부족또는 초과");
        }

        return $this;
    }


    /**
     * WEb Security. protect CSRF, XSS etc.
     *
     *
     * @param $rule
     * @return $this
     */
    public function doProtectXssInject($rule = "remove")
    {
        switch ($rule) {
            case "remove":
                $this->inputValue = \str_replace(array("'", "\"", "select", "union", "update", "insert", "delete", "script", "\;", "\:", "\?", "\n", "\0", "<", ">", "\x1a", '\\', '&', '<', '>'), "", $this->inputValue);
                break;
            case "replace":
                $this->inputValue = \str_replace(array("select", "union", "update", "insert", "delete", "script", "\n", "\0", "\x1a", '\\'), "", $this->inputValue);
                $this->inputValue = \str_replace("<", "&lt;", $this->inputValue);
                $this->inputValue = \str_replace(">", "&gt;", $this->inputValue);
                $this->inputValue = \str_replace("\"", "&quot;", $this->inputValue);
                $this->inputValue = \str_replace("'", "&apos;", $this->inputValue);
                break;
            case "default":
                break;
        }

        return $this;
    }


    /**
     * validate the value
     *
     * @param bool $isValidate validate or NOT
     * @param bool $isNullAble nullable is OK or NOT
     * @return bool|string
     */
    public function validate($isValidate = true, $isNullAble = true)
    {
        if ($isNullAble == true) {
            if ($this->inputValue == null or $this->inputValue == '') {
                return null;
            }
        }

        if ($isValidate == true) {
            if (array_key_exists($this->rule, $this->regExes)) {
                if (filter_var($this->inputValue, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => '!' . $this->regExes[$this->rule] . '!i'))) == false) {
                    throw new InvalidParameterException($this->inputKey . " : '" . $this->inputValue . "' Not Allowed");
                }
            }

            switch ($this->rule) {
                case 'boolean':
                    $filter = FILTER_VALIDATE_BOOLEAN;
                    break;
                case 'email':
                    $this->inputValue = substr($this->inputValue, 0, 254);
                    $filter = FILTER_VALIDATE_EMAIL;
                    break;
                case 'int':
                    $filter = FILTER_VALIDATE_INT;
                    break;
                case 'float':
                    $filter = FILTER_VALIDATE_FLOAT;
                    break;
                case 'ip':
                    $filter = FILTER_VALIDATE_IP;
                    break;
                case 'url':
                    $filter = FILTER_VALIDATE_URL;
                    break;
                case 'json':
                    if (!is_array(\json_decode($this->inputValue, true)) == true) {
                        throw new InvalidParameterException($this->inputKey . " : '" . $this->inputValue . "' Not Allowed");
                    }
                    $filter = "";
                    break;
                case 'array':
                    if (!is_array($this->inputValue)) {
                        throw new InvalidParameterException($this->inputKey . " : '" . $this->inputValue . "' Not Allowed");
                    }
                    $filter = "";
                    break;
                default:
                    $filter = null;
                    break;
            }

            if ($filter) {
                if (filter_var($this->inputValue, $filter) === false) {
                    throw new InvalidParameterException($this->inputKey . " : '" . $this->inputValue . "' Not Allowed");
                }
            }

        }

        if ($this->rule == "int") {
            return (int)$this->inputValue;
        }

        return $this->inputValue;
    }
}