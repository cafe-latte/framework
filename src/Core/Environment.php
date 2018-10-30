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
class Environment
{
    //Serer Info
    const SERVER_NAME = SERVER_NAME;
    const SERVER_TYPE = SERVER_TYPE;

    //Project Info
    const PROJECT_PATH = PROJECT_PATH;
    const PROJECT_URL = PROJECT_URL;
    const PROJECT_NAME = PROJECT_NAME;
    const PROJECT_VERSION = PROJECT_VERSION;
    const PROJECT_SECURITY_LEVEL = PROJECT_SECURITY_LEVEL;


    //Template(View) Info
    const VIEW_HTML_PATH = VIEW_PATH_HTML;
    const VIEW_PHP_PATH = VIEW_PATH_PHP;

    //Log Info
    const LOG_PATH = LOG_PATH;
    const LOG_LEVEL = LOG_LEVEL;


    //Upload Info
    const UPLOAD_PATH = UPLOAD_PATH;
}