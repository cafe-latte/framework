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

use CafeLatte\Exception\InvalidMethodRequestException;
use CafeLatte\Interfaces\HttpRequestInterface;
use CafeLatte\Interfaces\LoggerInterface;

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class HttpRequest implements HttpRequestInterface
{

    /**
     * HTTP Get
     */
    private $get;

    /**
     * HTTP Post..
     */
    private $post;

    /**
     * HTTP Delete
     */
    private $delete;

    /**
     * HTTP Put
     */
    private $put;

    /**
     * HTTP Headers
     */
    private $header;

    /**
     * HTTP cookie
     */
    private $cookie;

    /**
     * HTTP session
     */
    private $session;

    /**
     * HTTP Server
     */
    private $server;

    /**
     * Json Data
     */
    private $config;

    /**
     * HTTP Attach File
     */
    private $file;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @var
     */
    private $securityLevel;

    /**
     * @var
     */
    private $requestMethod;

    /**
     * @var null
     */
    private $requestUri;


    /**
     * @var null
     */
    public $routerUriName;


    /**
     * @var
     */
    public $parameters = [];


    /**
     * HttpRequest constructor.
     * @param Logger $log
     * @param $requestMethod
     * @param $requestUri
     * @param $config
     */
    public function __construct(Logger $log, $requestMethod, $requestUri, $config)
    {
        session_start();
        $this->log = $log;
        $this->log->debug("------------------------------------------------------------------", []);
        $this->requestMethod = $requestMethod;
        $this->requestUri = $requestUri;
        $splitUri = explode("?", $this->requestUri);
        $this->routerUriName = $splitUri[0];

        $this->setConfig($config);
        $this->setServer();
        $this->setHeader();
        $this->setCookie();
        $this->setSession();

        switch ($this->requestMethod) {
            case "POST":
                $this->setPost();
                break;
            case "GET":
                $this->setGet();
                break;
            case "PUT":
                $this->setPut();
                break;
            case "DELETE":
                $this->setDelete();
                break;
            default:
                throw new InvalidMethodRequestException("METHOD({$this->requestMethod}) Not Supported");
        }

        $this->setFile();
        $this->doSaveLogForAnalytics();
    }


    /** ----------------------------------------- * */

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return mixed
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     *
     * @return mixed
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     *
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     *
     * @return mixed
     */
    public function getServer()
    {
        return $this->session;
    }

    /**
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * to make more security
     *
     * @param $rawData
     * @return mixed
     */
    public function setSecurityLevel($rawData)
    {
        switch ($this->securityLevel) {
            case "low":
                break;
            case "normal":
                $rawData = \str_replace("<", "&lt;", $rawData);
                $rawData = \str_replace(">", "&gt;", $rawData);
                $rawData = \str_replace("\"", "&quot;", $rawData);
                $rawData = \str_replace("'", "&apos;", $rawData);
                break;
            case "high":
                $rawData = \str_replace("<", "", $rawData);
                $rawData = \str_replace(">", "", $rawData);
                $rawData = \str_replace("\"", "", $rawData);
                $rawData = \str_replace("'", "", $rawData);
                break;
        }

        return $rawData;
    }


    /**
     * GET Method and Parameter Information and assign via for framework
     */
    private function setGet()
    {
        if (isset($_GET)) {
            foreach ($_GET as $k => $v) {
                $this->log->debug("[ GET Params ]" . $k . ": " . $v, []);
                $this->get->$k = $this->setSecurityLevel($v);
                $this->parameters["get"][$k] = $this->setSecurityLevel($v);
            }
            unset($_GET);
        }
    }


    /**
     * Add Option Data
     *
     * @param $config
     */
    private function setConfig($config)
    {
        $this->securityLevel = $config->config['project']['security_level'];
        if ($config->config['config']) {
            foreach ($config->config['config'] as $k => $v) {
                $this->log->debug("[ CONFIG Params ]" . $k . ": " . $v, []);
                $this->config->$k = $this->setSecurityLevel($v);
                $this->parameters["config"][$k] = $this->setSecurityLevel($v);
            }
        }

    }

    /**
     * POST Method and Parameter Information and assign via for framework
     */
    private function setPost()
    {
        if ($this->parameters['header']["Content-Type"] == "application/x-www-form-urlencoded") {
            if (isset($_GET)) {
                foreach ($_GET as $k => $v) {
                    $this->log->debug("[ _POST Params ]" . $k . ": " . $v, []);
                    $this->post->$k = $this->setSecurityLevel($v);
                    $this->parameters["post"][$k] = $this->setSecurityLevel($v);
                }
                unset($_GET);
            }
        }

        if (isset($_POST)) {
            foreach ($_POST as $k => $v) {
                if (is_array($v) == true) {
                    $this->log->debug("[ _POST Params ]" . $k . ": " . json_encode($v), []);
                } else {
                    $this->log->debug("[ _POST Params ]" . $k . ": " . $v, []);
                }
                $this->post->$k = $this->setSecurityLevel($v);
                $this->parameters["post"][$k] = $this->setSecurityLevel($v);
            }


            $rawData = file_get_contents("php://input");
            if (isset($rawData)) {
                $this->log->debug("[ POST RawData ] : " . $rawData, []);
                $this->parameters["post"]["raw"] = $rawData;
                $this->post->raw = $rawData;
            }

            unset($_POST);
        }
    }

    /**
     * PUT Method and Parameter Information and assign via for framework
     */
    private function setPut()
    {
        if ($this->parameters['header']["Content-Type"] == "application/x-www-form-urlencoded") {
            if (isset($_GET)) {
                foreach ($_GET as $k => $v) {
                    $this->log->debug("[ PUT Params ]" . $k . ": " . $v, []);
                    $this->put->$k = $this->setSecurityLevel($v);
                    $this->parameters["put"][$k] = $this->setSecurityLevel($v);
                }
                unset($_GET);
            }

            $rawData = file_get_contents("php://input");
            parse_str($rawData, $_PUT);
            foreach ($_PUT as $k => $v) {
                $this->log->debug("[ PUT Params ]" . $k . ": " . $v, []);
                $this->put->$k = $this->setSecurityLevel($v);
                $this->parameters["put"][$k] = $this->setSecurityLevel($v);
            }
            if ($rawData) {
                $this->parameters["put"]['raw'] = $rawData;
                $this->log->debug("[ PUT RawData ] raw: " . $rawData, []);
            }
        }
    }


    /**
     * DELETE Method and Parameter Information and assign via for framework
     */
    private function setDelete()
    {
        if ($this->parameters['header']["Content-Type"] == "application/x-www-form-urlencoded") {
            if (isset($_GET)) {
                foreach ($_GET as $k => $v) {
                    $this->log->debug("[ DELETE Params ]" . $k . ": " . $v, []);
                    $this->delete->$k = $this->setSecurityLevel($v);
                    $this->parameters["delete"][$k] = $this->setSecurityLevel($v);
                }
                unset($_GET);
            }

            $rawData = file_get_contents("php://input");
            parse_str($rawData, $_DELETE);
            foreach ($_DELETE as $k => $v) {
                $this->log->debug("[ DELETE Params ]" . $k . ": " . $v, []);
                $this->delete->$k = $this->setSecurityLevel($v);
                $this->parameters["delete"][$k] = $this->setSecurityLevel($v);
            }
            if ($rawData) {
                $this->parameters["delete"]['raw'] = $rawData;
                $this->log->debug("[ DELETE RawData ] raw: " . $rawData, []);
            }
        }
    }

    /**
     *
     */
    private function setFile()
    {
        if (isset($_FILES)) {
            foreach ($_FILES as $k => $v) {
                if (is_array($v) == true) {
                    $this->log->debug("[ FILE Params ]" . $k . ": " . json_encode($v), []);
                } else {
                    $this->log->debug("[ FILE Params ]" . $k . ": " . $v, []);
                }

                $this->file->$k = $this->setSecurityLevel($v);
                $this->parameters["file"][$k] = $this->setSecurityLevel($v);
            }
            unset($_FILES);
        }
    }


    /**
     * Set Cookie Info
     */
    private function setCookie()
    {
        if (isset($_COOKIE)) {
            foreach ($_COOKIE as $k => $v) {
                $this->log->debug("[ COOKIE Params ]" . $k . ": " . $v, []);
                $this->cookie->$k = $this->setSecurityLevel($v);
                $this->parameters["cookie"][$k] = $this->setSecurityLevel($v);
            }
            unset($_COOKIE);
        }
    }

    /**
     * Set Header Info
     */
    private function setHeader()
    {
        foreach (getallheaders() as $k => $v) {
            $this->log->debug("[ HEADER Params ]" . $k . ": " . $v, []);
            $this->header->$k = $this->setSecurityLevel($v);
            $this->parameters["header"][$k] = $this->setSecurityLevel($v);
        }
    }

    /**
     * Set _SERVER info
     */
    private function setServer()
    {
        if (isset($_SERVER)) {
            foreach ($_SERVER as $k => $v) {
                $this->log->debug("[ SERVER Params ]" . $k . ": " . $v, []);
                $this->server->$k = $v;
                $this->parameters["server"][$k] = $v;
            }
            unset($_SERVER);
        }
    }


    /**
     * Set _SESSION Info
     */
    private function setSession()
    {
        if (isset($_SESSION)) {
            foreach ($_SESSION as $k => $v) {
                $this->log->debug("[ SESSION Params ]" . $k . ": " . $v, []);
                $this->session->$k = $this->setSecurityLevel($v);
                $this->parameters["session"][$k] = $this->setSecurityLevel($v);
            }
        }
    }
    /** ----------------------------------------- * */

    /**
     * get a GET Value using key
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->get->$key;
    }

    /**
     * get a POST Value using key
     *
     * @param $key
     * @return mixed
     */
    public function post($key)
    {
        return $this->post->$key;
    }

    /**
     * get a DELETE Value using key
     *
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        return $this->delete->$key;
    }

    /**
     * get a PUT Value using key
     *
     * @param $key
     * @return mixed
     */
    public function put($key)
    {
        return $this->put->$key;
    }


    /**
     * get a _COOKIE Value using key
     *
     * @param $key
     * @return mixed
     */
    public function cookie($key)
    {
        return $this->cookie->$key;
    }

    /**
     * get a Attach File Information
     *
     * @param $key
     * @return mixed
     */
    public function file($key)
    {
        return $this->file->$key;
    }

    /**
     * get a Header Value using key
     *
     * @param $key
     * @return mixed
     */
    public function header($key)
    {
        return $this->header->$key;
    }

    /**
     * get a _SESSION Value using key
     *
     * @param $key
     * @return mixed
     */
    public function session($key)
    {
        return $this->session->$key;
    }


    /**
     * get a PUT Value using key
     *
     * @param $key
     * @return mixed
     */
    public function server($key)
    {
        return $this->server->$key;
    }

    /**
     * get all request info
     * @return array
     */
    public function getAllParams()
    {

        return $this->parameters;
    }


    /**
     * leave all request log for analytics
     */
    private function doSaveLogForAnalytics()
    {
        $log = new Logger();
        $log->log("debug", json_encode($this->parameters), [], \date("Y-m-d") . ".json");
    }


}
