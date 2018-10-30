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
interface HttpRequestInterface
{

    public function get($key);

    public function post($key);

    public function server($key);

    public function delete($key);

    public function put($key);

    public function file($key);

    public function cookie($key);

    public function header($key);

    public function session($key);

    public function getPost();

    public function getGet();

    public function getHeader();

    public function getCookie();

    public function getSession();

    public function getAllParams();


}