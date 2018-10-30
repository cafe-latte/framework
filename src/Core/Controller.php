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


use CafeLatte\Interfaces\HttpRequestInterface;
use CafeLatte\Interfaces\LoggerInterface;


/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class Controller extends ModelView
{
    protected $request;
    protected $log;


    /**
     * Controller constructor.
     *
     * @param HttpRequestInterface $request
     * @param LoggerInterface $log
     */
    public function __construct(HttpRequestInterface $request, LoggerInterface $log)
    {
        parent::__construct();
        $this->request = $request;
        $this->log = $log;
    }

}