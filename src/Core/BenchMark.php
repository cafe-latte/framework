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

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class BenchMark
{

    const NOT_INITIALIZED = -1;

    private $startTime;
    private $endTime;

    /**
     * constructor.
     */
    function __construct()
    {
        $this->startTime = -1;
        $this->endTime = -1;
    }


    /**
     * the start point to calculate a runtime.
     *
     * @return bool
     */
    public function start()
    {
        return $this->checkNow('startTime');
    }

    /**
     * the end point to calculate a runtime.
     *
     * @return bool
     */
    public function end()
    {
        return $this->checkNow('endTime');
    }

    /**
     * to get the runtime.
     *
     * @param int $precision
     * @return boolean
     */
    public function getRuntime(int $precision = 10)
    {
        if ($this->startTime == $this::NOT_INITIALIZED || $this->endTime == $this::NOT_INITIALIZED) {
            return FALSE;
        }

        $runTime = round($this->endTime - $this->startTime, $precision);

        return $runTime;
    }

    /**
     * to set the current micro time.
     *
     * @param string $storeVarName
     * @return bool
     */
    private function checkNow(string $storeVarName) : bool
    {
        $now = $this->microtimeFloat();
        $this->{$storeVarName} = $now;
        return true;
    }


    /**
     * to get the current micro time as float type.
     *
     * @return float
     */
    private function microTimeFloat() : float
    {
        list($use, $sec) = explode(" ", microtime());
        return ((float) $use + (float) $sec);
    }

}
