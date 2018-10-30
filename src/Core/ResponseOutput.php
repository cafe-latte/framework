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


use CafeLatte\Exception\InvalidLogicException;
use CafeLatte\Helpers\Parser;

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class ResponseOutput
{

    /**
     * Response Type
     *
     * @var
     */
    public $responseType;

    /**
     * Response Status Code in Header
     * @var
     */
    public $responseStatusCode;

    /**
     * Response Status Message in Header
     * @var
     */
    public $responseStatusMessage;


    /**
     * Response the return code on body
     *
     * @var
     */
    public $bodyCode;

    /**
     * Response the body message on body
     * @var
     */
    public $bodyMessage;

    /**
     * add message on body data
     *
     * @var
     */
    public $bodyAddMessage;


    /**
     * add message on body
     * @var
     */
    public $bodyData;


    /**
     * @var
     */
    public $isDisplayCode;

    /**
     * @var
     */
    public $path;

    /**
     * @var
     */
    public $redirectUrl;

    /**
     * @var array
     */
    public $layout = [];

    /**
     * @param string $key
     * @param string $value
     */
    public function addHeader(string $key, string $value)
    {
        header("{$key}: {$value}");
    }


    /**
     * @return null
     * @throws \Exception
     */
    public function output()
    {

        if (!$this->responseStatusCode) {
            throw new InvalidLogicException("NO Response Status Code Call");
        }

        header("HTTP/1.0 {$this->responseStatusCode} {$this->responseStatusMessage}");

        switch ($this->responseType) {
            case ResponseType::JSON:
                $this->addHeader("Content-type", "application/json");

                if ($this->responseStatusCode == 200) {
                    if ($this->isDisplayCode == true) {
                        return json_encode(array('code' => $this->bodyCode, 'message' => "{$this->bodyMessage}{$this->bodyAddMessage}", 'body' => $this->bodyData),JSON_UNESCAPED_UNICODE);
                    } else {
                        return json_encode($this->bodyData,JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    return json_encode(array('code' => $this->bodyCode, 'message' => "{$this->bodyMessage}{$this->bodyAddMessage}"));
                }


            case ResponseType::XML:
                $this->addHeader("Content-type", "text/xml; charset=utf-8");
                echo '<?xml version="1.0" encoding="utf-8"?>';

                if ($this->responseStatusCode == 200) {
                    if ($this->isDisplayCode == true) {
                        return Parser::arrayToXml(array('code' => $this->bodyCode, 'message' => "{$this->bodyMessage}{$this->bodyAddMessage}", 'body' => $this->bodyData));
                    } else {
                        return Parser::arrayToXml($this->bodyData);
                    }
                } else {
                    return Parser::arrayToXml(array('code' => $this->bodyCode, 'message' => "{$this->bodyMessage}{$this->bodyAddMessage}"));
                }
            case ResponseType::HTML:
                $this->addHeader("Content-type", "text/html");

                $tpl = new Template(Environment::VIEW_PHP_PATH);
                $tpl->addAssign($this->bodyData);
                $tpl->setDefine($this->layout);
                $tpl->execute("LAYOUT");

                return null;
            case ResponseType::REDIRECT:
                $this->addHeader("Location", $this->redirectUrl);
                return null;
            default :
                throw new InvalidLogicException("Not To Set Response Type");
        }
    }

}