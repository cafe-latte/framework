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

    public $redirectUrl;

    public $format;

    private $enum;

    private $regExes = array(
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
    public function setFormatValue($format): FormValidator
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
    public function setReplaceValue($key, string $value): FormValidator
    {
        if ($this->inputValue) {
            $this->inputValue = str_replace($key, $value, $this->inputValue);
        }

        return $this;
    }

    /**
     * If params failed, move to the url
     * @param string $url
     * @return $this
     */
    public function setValidationFailUrl(string $url): FormValidator
    {
        if ($url) {
            $this->redirectUrl = $url;
        }

        return $this;
    }


    /**
     * set rule.
     *
     * @param mixed $rule
     * @return $this
     */
    public function setRule($rule): FormValidator
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
    public function setMinMaxLength(int $min, int $max): FormValidator
    {
        if ($min > strlen($this->inputValue) or strlen($this->inputValue) > $max) {
            $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' 의 길이(length)가 작거나 초과 ( {$min} ~ {$max} )";
            if ($this->redirectUrl) {
                echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                exit;
            }
            throw new InvalidParameterException($errorMessage);
        }

        return $this;
    }


    /**
     * check min / max length
     *
     * @param int $min
     * @param int $max
     * @return $this
     */
    public function setMinMaxValue(int $min, int $max): FormValidator
    {
        if ($min > $this->inputValue or $this->inputValue > $max) {
            $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' 의 값(value)이 작거나 초과 ( {$min} ~ {$max} )";
            if ($this->redirectUrl) {
                echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                exit;
            }
            throw new InvalidParameterException($errorMessage);
        }

        return $this;
    }

    /**
     * check min / max length
     *
     * @param array $value
     * @return $this
     */
    public function setEnum(array $value): FormValidator
    {
        if (!is_array($value) == true) {
            $errorMessage = "enum Rule must be array";
            if ($this->redirectUrl) {
                echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                exit;
            }
            throw new InvalidParameterException($errorMessage);
        }
        $this->enum = $value;
        return $this;
    }


    /**
     * WEb Security. protect CSRF, XSS etc.
     *
     *
     * @param string $rule
     * @return $this
     */
    public function doProtectXssInject(string $rule = "remove"): FormValidator
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
    public function validate(bool $isValidate = true, bool $isNullAble = true)
    {
        if ($isNullAble == true) {
            if ($this->inputValue == null or $this->inputValue == '') {
                return null;
            }
        }

        if ($isValidate == true) {
            if (array_key_exists($this->rule, $this->regExes)) {
                $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed";
                if (filter_var($this->inputValue, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => '!' . $this->regExes[$this->rule] . '!i'))) == false) {
                    if ($this->redirectUrl) {
                        echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                        exit;
                    }
                    throw new InvalidParameterException($errorMessage);
                }
            }

            $filter = null;

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
                case 'string':
                    if (is_null($this->inputValue)) {
                        $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed(Null)";
                        if ($this->redirectUrl) {
                            echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                            exit;
                        }
                        throw new InvalidParameterException($errorMessage);
                    }
                    if (empty($this->inputValue)) {
                        $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed(Empty)";
                        if ($this->redirectUrl) {
                            echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                            exit;
                        }
                        throw new InvalidParameterException($errorMessage);
                    }
                    break;
                case 'enum':
                    if (!in_array($this->inputValue, $this->enum)) {
                        $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed(Not Json)";
                        if ($this->redirectUrl) {
                            echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                            exit;
                        }
                        throw new InvalidParameterException($errorMessage);
                    }
                    break;
                case 'file':
                    if ($this->inputValue['size'] == 0) {
                        $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed(Empty Array)";
                        if ($this->redirectUrl) {
                            echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                            exit;
                        }
                        throw new InvalidParameterException($errorMessage);
                    }
                    break;
                case 'null':
                    if (is_null($this->inputValue)) {
                        $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed(Is Null)";
                        if ($this->redirectUrl) {
                            echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                            exit;
                        }
                        throw new InvalidParameterException($errorMessage);
                    }
                    break;
                case 'empty':
                    if (empty($this->inputValue)) {
                        $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed(Is Empty)";
                        if ($this->redirectUrl) {
                            echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                            exit;
                        }
                        throw new InvalidParameterException($errorMessage);
                    }
                    break;
                case 'json':
                    if (!is_array(\json_decode($this->inputValue, true)) == true) {
                        $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed(Not Json)";
                        if ($this->redirectUrl) {
                            echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                            exit;
                        }
                        throw new InvalidParameterException($errorMessage);
                    }
                    $filter = "";
                    break;
                case 'array':
                    if (!is_array($this->inputValue)) {
                        $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed(Not Array)";
                        if ($this->redirectUrl) {
                            echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                            exit;
                        }
                        throw new InvalidParameterException($errorMessage);
                    }
                    $filter = "";
                    break;
                default:
                    $filter = null;
                    break;
            }

            if ($filter) {
                if (filter_var($this->inputValue, $filter) === false) {
                    $errorMessage = $this->inputKey . " : '" . $this->inputValue . "' Not Allowed";
                    if ($this->redirectUrl) {
                        echo "<script>location.href = '{$this->redirectUrl}?code=400&error_name=InvalidParameterException&message={$errorMessage}';</script>";
                        exit;
                    }
                    throw new InvalidParameterException($errorMessage);
                }
            }

        }

        if ($this->rule == "int") {
            return (int)$this->inputValue;
        }

        return $this->inputValue;
    }
}