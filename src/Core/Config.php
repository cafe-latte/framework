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

use CafeLatte\Exception\ConfigSettingFailException;
use CafeLatte\Helpers\Parser;

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class Config
{
    public $config = [];


    /**
     * Config constructor.
     * @param $settingPath
     */
    public function __construct($settingPath)
    {
        try {
            $setting = Parser::jsonToArray($settingPath);

            define("SERVER_NAME", $setting['server_name']);
            define("SERVER_TYPE", $setting['server_type']);

            define("PROJECT_PATH", $setting['project']['path']);
            define("PROJECT_URL", $setting['project']['url']);
            define("PROJECT_NAME", $setting['project']['name']);
            define("PROJECT_VERSION", $setting['project']['version']);
            define("PROJECT_SECURITY_LEVEL", $setting['project']['security_level']);

            define("LOG_LEVEL", $setting['log']['level']);
            define("LOG_PATH", $setting['log']['path']);

            define("VIEW_PATH_PHP", $setting['template']['output']);
            define("VIEW_PATH_HTML", $setting['template']['input']);

            define("UPLOAD_PATH", $setting['upload']['path']);

            $this->doPathValidate(PROJECT_PATH, false);
            $this->doPathValidate(VIEW_PATH_HTML, false);
            $this->doPathValidate(VIEW_PATH_PHP);
            $this->doPathValidate(UPLOAD_PATH);
            $this->doPathValidate(LOG_PATH);

        } catch (ConfigSettingFailException  $e) {
            header("HTTP/1.0 400 ERROR_FAIL_CONFIG_SETTING");
            header("Content-type: application/json");
            echo json_encode(array('code' => $e->getCode(), 'message' => $e->getMessage()));
            exit;
        }

        $this->config = $setting;

        return $this;

    }

    /**
     * to do validate config params.
     *
     * @param string|null $pathValue
     * @param bool $isWritable
     */
    public function doPathValidate(string $pathValue = null, $isWritable = true)
    {
        if ($pathValue != null) {
            if (!is_dir($pathValue)) {
                throw new ConfigSettingFailException("`{$pathValue}` does NOT existed", '400');
            }

            if ($isWritable == true) {
                if (!is_writable($pathValue)) {
                    throw new ConfigSettingFailException("`{$pathValue}`is NOT writable", '400');
                }
            }

        }
    }
}