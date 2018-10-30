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
use CafeLatte\Exception\InvalidUrlRequestException;


/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class Router
{

    private $routes = array();
    private $requestMethod;
    private $uri;
    private $serverName;
    private $log;

    /**
     * Router constructor.
     * @param Logger $log
     * @param $requestMethod
     * @param $requestUri
     */
    public function __construct(Logger $log , $requestMethod , $requestUri)
    {
        $this->requestMethod = $requestMethod;
        $this->uri = $requestUri;
        $this->serverName = filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->log = $log;

        $this->log->debug($this->requestMethod, []);
        $this->log->debug($this->uri, []);
    }

    /**
     * allow to access via the get request
     *
     * @param string $pattern
     * @param callable $callback
     */
    public function get(string $pattern, callable $callback)
    {
        if ($this->requestMethod === "GET") {
            $this->mapRoute($pattern, $callback);
        }
    }

    /**
     * allow to access via the post request
     *
     * @param string $pattern
     * @param callable $callback
     */
    public function post(string $pattern, callable $callback)
    {
        if ($this->requestMethod === "POST") {
            $this->mapRoute($pattern, $callback);
        }
    }

    /**
     * allow to access via the delete request
     *
     * @param string $pattern
     * @param callable $callback
     */
    public function delete(string $pattern, callable $callback)
    {
        if ($this->requestMethod === "DELETE") {
            $this->mapRoute($pattern, $callback);
        }
    }

    /**
     * allow to access via the delete request
     *
     * @param string $pattern
     * @param callable $callback
     */
    public function put(string $pattern, callable $callback)
    {
        if ($this->requestMethod === "PUT") {
            $this->mapRoute($pattern, $callback);
        }
    }

    /**
     * map
     *
     * @param string $pattern
     * @param callable $callback
     */
    private function mapRoute(string $pattern, callable $callback)
    {
        $word = self::getPattern($pattern);

        $pattern = '/^' . str_replace('/', '\/', $word) . '$/';
        $this->routes[$pattern] = $callback;
    }

    /**
     * input patten re change
     *
     * @param string $pattern
     * @return string
     */
    private static function getPattern(string $pattern)
    {
        $keywords = preg_split("/\\//", $pattern);
        $i = '0';
        $word = "";
        foreach ($keywords as $keyword) {
            $i++;
            if (preg_match("/:/i", $keyword)) {
                $word .= "([a-zA-Z0-9-._]+)";
            } else {
                $word .= $keyword;
            }
            if (count($keywords) != $i) {
                $word .= "/";
            }
        }

        return $word;
    }

    /**
     * userCallable
     *
     * @param callable $callback
     * @param array $params
     * @return mixed
     */
    private static function userCallable(callable $callback, array $params)
    {

        return call_user_func_array($callback, array_values($params));
    }

    /**
     * execute
     */
    public function run()
    {
        $url = "";
        $splitUri = explode("?", $this->uri);
        $base = str_replace('\\', '/', dirname($this->serverName));

        if (strpos($splitUri['0'], $base) == false) {
            $url = substr($splitUri['0'], strlen($base));
        }

        $totalRouteCnt = count($this->routes);


        $i = 0;
        foreach ($this->routes as $pattern => $callback) {
            if (preg_match($pattern, $url, $params)) {
                array_shift($params);

                self::userCallable($callback, $params);
            } else {
                $i++;
            }
        }

        if ($totalRouteCnt == $i) {
            throw new InvalidUrlRequestException($url);
        }
    }

}
