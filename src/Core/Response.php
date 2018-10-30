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
class Response extends ResponseOutput
{

    private static $instance = NULL;

    //SUCCESS
    CONST SUCCESS_CODE_200 = "Success";

    //CORE ERROR
    CONST ERROR_CODE_1000 = "Invalid Parameter OR Lack Of Parameter";
    CONST ERROR_CODE_1001 = "Logic Exception.";
    CONST ERROR_CODE_1002 = "Wrong URL Request.";
    CONST ERROR_CODE_1003 = "Permission Exception";
    CONST ERROR_CODE_1004 = "Wrong Method Request ";
    CONST ERROR_CODE_1005 = "Fail To Template";
    CONST ERROR_CODE_1006 = "Config Setting Fail";

    //TOKEN ERROR
    CONST ERROR_CODE_1100 = "Token Expired.";
    CONST ERROR_CODE_1101 = "Invalid Token";
    CONST ERROR_CODE_1102 = "User Existed";

    //FILE ERROR
    CONST ERROR_CODE_1200 = "File Upload Fail";
    CONST ERROR_CODE_1201 = "File Not Found";

    //DB
    CONST ERROR_CODE_1300 = "DB Connected Fail";
    CONST ERROR_CODE_1301 = "DB Not Found Row";
    CONST ERROR_CODE_1302 = "DB Query Syntax Error / DB Error";
    CONST ERROR_CODE_1303 = "DB Duplication Request";
    CONST ERROR_CODE_1304 = "DB UnMatched Data";

    //UN EXCEPTED ERROR
    CONST ERROR_CODE_2000 = "Un Excepted Error";



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
     * Response Code in Body
     * @var
     */
    public $bodyCode;

    /**
     * Response Body in Body
     * @var
     */
    public $bodyData;

    /**
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
     * @var
     */
    public $isDisplayCode;

    /**
     * @var
     */
    public $mainFile;

    /**
     * @var
     */
    public $path;

    /**
     * @var array
     */
    public $layout = [];

    /**
     * @var
     */
    public $redirectUrl;


    /**
     * @return Response|null
     */
    public static function create()
    {
        if (self::$instance == NULL) {
            self::$instance = new Response();
        }
        return self::$instance;

    }

    /**
     * Response constructor.
     */
    public function __construct()
    {

        $this->setResponseStatusCode();
        $this->setBodyCode();
        $this->isDisplayCode();

    }

    /**
     * @param string|null $addMessage
     * @return $this
     */
    public function addMessage(string $addMessage = null)
    {
        if ($addMessage != null) {
            $this->bodyAddMessage = "(" . $addMessage . ")";
        }

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }


    /**
     * @param array $layout
     * @return $this
     */
    public function setViewLayout(array $layout)
    {
        $this->layout = $layout;
        return $this;
    }


    /**
     * @param string $mainFile
     * @return $this
     */
    public function setViewFile(string $mainFile)
    {
        $this->mainFile = $mainFile;
        return $this;
    }

    /**
     * @param $body
     * @return $this
     */
    public function setBodyData($body)
    {
        $this->bodyData = $body;
        return $this;
    }

    /**
     * @param bool $isBoolean
     * @return $this
     */
    public function isDisplayCode($isBoolean = true)
    {
        $this->isDisplayCode = $isBoolean;
        return $this;
    }


    /**
     * @param $bodyCode
     * @return $this
     */
    public function setBodyCode(int $bodyCode = 200)
    {
        $this->bodyCode = $bodyCode;
        switch ($bodyCode) {
            case 200;
                $this->responseStatusMessage = "SUCCESS";
                $this->bodyMessage = self::SUCCESS_CODE_200;
                break;
            case 1000;
                $this->responseStatusMessage = "ERROR_INVALID_PARAMETER";
                $this->bodyMessage = self::ERROR_CODE_1000;
                break;
            case 1001;
                $this->responseStatusMessage = "ERROR_INVALID_LOGIC";
                $this->bodyMessage = self::ERROR_CODE_1001;
                break;
            case 1002;
                $this->responseStatusMessage = "ERROR_INVALID_URL_REQUEST";
                $this->bodyMessage = self::ERROR_CODE_1002;
                break;
            case 1003;
                $this->responseStatusMessage = "ERROR_PERMISSION_NOT_ALLOWED";
                $this->bodyMessage = self::ERROR_CODE_1003;
                break;
            case 1004;
                $this->responseStatusMessage = "ERROR_INVALID_METHOD_REQUEST";
                $this->bodyMessage = self::ERROR_CODE_1003;
                break;
            case 1005;
                $this->responseStatusMessage = "ERROR_FAIL_TO_TEMPLATE";
                $this->bodyMessage = self::ERROR_CODE_1003;
                break;
            case 1006;
                $this->responseStatusMessage = "ERROR_FAIL_CONFIG_SETTING";
                $this->bodyMessage = self::ERROR_CODE_1003;
                break;
            case 1100;
                $this->responseStatusMessage = "ERROR_TOKEN_EXPIRED";
                $this->bodyMessage = self::ERROR_CODE_1100;
                break;
            case 1101;
                $this->responseStatusMessage = "ERROR_TOKEN_INVALID";
                $this->bodyMessage = self::ERROR_CODE_1101;
                break;
            case 1102;
                $this->responseStatusMessage = "ERROR_UN_REGISTER_USER";
                $this->bodyMessage = self::ERROR_CODE_1102;
                break;
            case 1200;
                $this->responseStatusMessage = "ERROR_FILE_UPLOAD_FAIL";
                $this->bodyMessage = self::ERROR_CODE_1200;
                break;
            case 1201;
                $this->responseStatusMessage = "ERROR_FILE_NOT_FOUND";
                $this->bodyMessage = self::ERROR_CODE_1201;
                break;
            case 1300;
                $this->responseStatusMessage = "ERROR_DATABASE_CONNECT_FAIL";
                $this->bodyMessage = self::ERROR_CODE_1301;
                break;
            case 1301;
                $this->responseStatusMessage = "ERROR_DATABASE_NOT_FOUND";
                $this->bodyMessage = self::ERROR_CODE_1301;
                break;
            case 1302;
                $this->responseStatusMessage = "ERROR_DATABASE_QUERY_FAIL";
                $this->bodyMessage = self::ERROR_CODE_1302;
                break;
            case 1303;
                $this->responseStatusMessage = "ERROR_DATABASE_DUPLICATION";
                $this->bodyMessage = self::ERROR_CODE_1303;
                break;
            case 1304;
                $this->responseStatusMessage = "ERROR_DATABASE_UNMATCHED";
                $this->bodyMessage = self::ERROR_CODE_1304;
                break;
            case 2000;
                $this->responseStatusMessage = "ERROR_UN_EXCEPTED_CODE";
                $this->bodyMessage = self::ERROR_CODE_2000;
                break;
            default;
                $this->responseStatusCode = 400;
                $this->responseStatusMessage = "ERROR_UN_EXCEPTED_CODE";
                $this->bodyMessage = "ERROR_UN_EXCEPTED_CODE";
                break;
        }

        if (isset($this->bodyAddMessage)) {
            $this->bodyMessage = $this->responseStatusMessage . "{$this->bodyAddMessage}";
        }

        return $this;
    }


    /**
     * @param string $returnType
     * @return $this
     */
    public function setResponseType(string $returnType)
    {
        $this->responseType = $returnType;
        return $this;
    }


    /**
     * @param int $statusCode
     * @return $this
     */
    public function setResponseStatusCode(int $statusCode = 200)
    {
        $this->responseStatusCode = $statusCode;

        return $this;
    }

    /**
     * @param string $statusMessage
     * @return $this
     */
    public function setResponseStatusMessage(string $statusMessage)
    {
        $this->responseStatusMessage = $statusMessage;

        return $this;
    }


    /**
     * @param $redirectUrl
     * @return $this
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }


    /**
     * @return null
     */
    public function run()
    {
        return $this->output();
    }


}