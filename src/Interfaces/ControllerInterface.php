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
 * @author Thorpe Lee <koangbok@gmail.com>
 */
interface ControllerInterface
{

    /**
     * 컨트롤러 인터페이스
     *
     * ControllerInterface constructor.
     * @param $request
     * @param LoggerInterface $log
     */
    public function __construct(HttpRequestInterface $request, LoggerInterface $log);


}
