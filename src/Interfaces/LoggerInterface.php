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

namespace CafeLatte\Interfaces;

/**
 * Describes a logger instance
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data, the only assumption that
 * can be made by implementors is that if an Exception instance is given
 * to produce a stack trace, it MUST be in a key named "exception".
 *
 * See https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * for the full interface specification.
 */
interface LoggerInterface
{

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function emergency(string $message, array $context = array(), $filename = null);

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
    public function alert(string $message, array $context = array(), $filename = null);

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
    public function critical(string $message, array $context = array(), $filename = null);

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function error(string $message, array $context = array(), $filename = null);

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
    public function warning(string $message, array $context = array(), $filename = null);

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function notice(string $message, array $context = array(), $filename = null);

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
    public function info(string $message, array $context = array(), $filename = null);

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function debug(string $message, array $context = array(), $filename = null);

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @param null $filename
     * @return null
     */
    public function log(string $level, string $message, array $context = array(), $filename = null);

}
