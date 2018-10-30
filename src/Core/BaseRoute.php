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

use CafeLatte\Exception\CustomException;
use CafeLatte\Exception\DatabaseDuplicatedException;
use CafeLatte\Exception\DatabaseNotFoundException;
use CafeLatte\Exception\DatabaseSyntaxException;
use CafeLatte\Exception\DatabaseUnmatchedException;
use CafeLatte\Exception\FileNotFoundException;
use CafeLatte\Exception\FileUploadFailException;
use CafeLatte\Exception\InvalidLogicException;
use CafeLatte\Exception\InvalidMethodRequestException;
use CafeLatte\Exception\InvalidParameterException;
use CafeLatte\Exception\InvalidTokenException;
use CafeLatte\Exception\InvalidUrlRequestException;
use CafeLatte\Exception\PermissionException;
use CafeLatte\Exception\TemplateFailException;
use CafeLatte\Exception\TokenExpiredException;
use CafeLatte\Exception\UnExpectedException;

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class BaseRoute
{
    protected $log;
    protected $request;
    protected $router;
    protected $timer;
    protected $result;
    protected $config;
    protected $response;


    /**
     * BaseRoute constructor.
     * @param $setting
     */
    public function __construct($setting)
    {
        $requestMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
        $requestUri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $this->config = new Config($setting);
            $this->log = new Logger();
            $this->request = new HttpRequest($this->log, $requestMethod, $requestUri, $this->config);
            $this->router = new Router($this->log, $requestMethod, $requestUri);
            $this->timer = new BenchMark();
            $this->response = Response::create();

        } catch (InvalidMethodRequestException $e) {
            $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode(1004)->addMessage($e->getMessage())->run();
        }
    }

    /**
     * execute
     */
    public function execute()
    {

        $this->timer->start();

        if (!$this->result) {
            try {
                $this->routing();
            } catch (InvalidParameterException $e) {
                $this->log->emergency($e, []);

                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1000;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;

                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (InvalidLogicException $e) {
                $this->log->emergency($e, []);

                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1001;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;

                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (InvalidUrlRequestException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1002;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (PermissionException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1003;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (InvalidMethodRequestException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1004;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (TemplateFailException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1005;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (TokenExpiredException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1100;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (InvalidTokenException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1101;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (FileNotFoundException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1201;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (FileUploadFailException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1202;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (DatabaseNotFoundException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1301;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (DatabaseSyntaxException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1302;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (DatabaseDuplicatedException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1303;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (DatabaseUnmatchedException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 1304;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (UnExpectedException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 2000;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (CustomException $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 2000;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            } catch (\Exception $e) {
                $this->log->emergency($e, []);
                $e->getCode() ? $bodyCode = $e->getCode() : $bodyCode = 2000;
                $e->getMessage() ? $bodyMessage = $e->getMessage() : $bodyMessage = null;
                $this->result = $this->response->setResponseType("json")->setResponseStatusCode(400)->setBodyCode($bodyCode)->addMessage($bodyMessage)->run();
            }

            $this->timer->end();
        }
        $this->log->debug("RUNTIME |" . " {$this->timer->getRuntime()}", []);
        $this->response();
    }

    /**
     * finally view response data
     */
    public function response()
    {
        if ($this->result) {
            echo $this->result;
        }
    }
}