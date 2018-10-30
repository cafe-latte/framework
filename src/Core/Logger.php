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

use CafeLatte\Helpers\Date;
use CafeLatte\Interfaces\LoggerInterface;

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class Logger implements LoggerInterface
{

    private $logPath;
    private $logLevel;

    public function __construct(string $logPath = Environment::LOG_PATH)
    {
        $this->logPath = $logPath;
        $this->getLogLevel();
    }

    /**
     * 일자별 로그파일명을 뽑는다.
     *
     * @access private
     * @return string  $fileName 파일명
     */
    private static function getFileName(): string
    {
        $fileName = \date("Y-m-d") . ".txt";
        return $fileName;
    }

    /**
     * 서버의 환경설정값에서  로그 레벨값을 가져온다.
     */
    private function getLogLevel()
    {
        switch (Environment::LOG_LEVEL) {
            case "emergency":
                $this->logLevel = 8;
                break;
            case "alert":
                $this->logLevel = 7;
                break;
            case "critical":
                $this->logLevel = 6;
                break;
            case "error":
                $this->logLevel = 5;
                break;
            case "warning":
                $this->logLevel = 4;
                break;
            case "notice":
                $this->logLevel = 3;
                break;
            case "info":
                $this->logLevel = 2;
                break;
            case "debug":
                $this->logLevel = 1;
                break;
            default:
                $this->logLevel = 1;
                break;
        }
    }


    /**
     * Leave Log
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     */
    private function setLogger(string $message, array $context = [], $filename = null)
    {
        if (!$filename) {
            $filename = self::getFileName();
        }

        $file = $this->logPath . $filename;

        if (!file_exists($file)) {
            touch($file);
            $handle = fopen($file, 'r+');
            exec("chmod 777" . $file);
        } else {
            $handle = fopen($file, 'a+');
        }

        if ($context) {
            fwrite($handle, $message . json_encode($context) . "\n");
        } else {
            fwrite($handle, $message . "\n");
        }

        fclose($handle);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function emergency(string $message, array $context = null, $filename = null)
    {
        if ($this->logLevel <= 8) {
            $message = "[" . Date::getCurrentTime() . "] EMERGENCY : " . $message;
            $this->setLogger($message, $context, $filename);
        }
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function alert(string $message, array $context = array(), $filename = null)
    {
        if ($this->logLevel <= 7) {
            $message = "[" . Date::getCurrentTime() . "] ALERT : " . $message;
            $this->setLogger($message, $context, $filename);
        }
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function critical(string $message, array $context = array(), $filename = null)
    {
        if ($this->logLevel <= 6) {
            $message = "[" . Date::getCurrentTime() . "] critical : " . $message;
            $this->setLogger($message, $context, $filename);
        }
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function error(string $message, array $context = array(), $filename = null)
    {
        if ($this->logLevel <= 5) {
            $message = "[" . Date::getCurrentTime() . "] ERROR : " . $message;
            $this->setLogger($message, $context, $filename);
        }
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function warning(string $message, array $context = array(), $filename = null)
    {
        if ($this->logLevel <= 4) {
            $message = "[" . Date::getCurrentTime() . "] WARNING : " . $message;
            $this->setLogger($message, $context, $filename);
        }
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function notice(string $message, array $context = array(), $filename = null)
    {
        if ($this->logLevel <= 3) {
            $message = "[" . Date::getCurrentTime() . "] NOTICE : " . $message;
            $this->setLogger($message, $context, $filename);
        }
    }

    /**
     * Interesting events.
     *
     * Example: User _logs in, SQL _logs.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function info(string $message, array $context = array(), $filename = null)
    {
        if ($this->logLevel <= 2) {
            $message = "[" . Date::getCurrentTime() . "] INFO : " . $message;
            $this->setLogger($message, $context, $filename);
        }
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function debug(string $message, array $context = array(), $filename = null)
    {
        if ($this->logLevel == 1) {
            $message = "[" . Date::getCurrentTime() . "] DEBUG : " . $message;
            $this->setLogger($message, $context, $filename);
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function log(string $level, string $message, array $context = array(), $filename = null)
    {
        $this->setLogger($message, $context = [], $filename);
    }

}

