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

use CafeLatte\Exception\TemplateFailException;

/**
 * @author Thorpe Lee <koangbok@gmail.com>
 */
class Template
{
    public $var = [];
    public $template = [];
    public $compileDir;


    /**
     * Template constructor.
     * @param string $compileDir
     */
    public function __construct(string $compileDir)
    {
        $this->compileDir = $compileDir;
    }

    /**
     * @param array $arg
     * @return $this
     */
    public function setDefine(array $arg)
    {
        foreach ($arg as $fid => $htmlFileName) {
            $this->template[$fid] = array('tpl', $htmlFileName);
        }
        return $this;
    }

    /**
     * 변수및 배열등을 담는다.
     *
     * @param string $arg
     * @return string|array $value
     */
    public function setAssign(string $arg, $value)
    {
        $this->var['templateData'][$arg] = $value;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function addAssign($value)
    {
        $this->var['templateData'] = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssign()
    {
        return $this->var['templateData'];
    }

    /**
     * 템플릿 파일을 실행한다.
     *
     * @param string $fid
     * @throws \Exception
     */
    public function execute(string $fid)
    {
        if (!isset($this->template[$fid])) {
            throw new TemplateFailException("No `Html` Template File", "200");
        }
        $tpl = $this->template[$fid];

        $cplPath = $this->setCompileWithTemplateFile($tpl[1]);
        $this->includeTpl($cplPath);
    }

    /**
     * 템플릿 파일로 만들어진 php 파일
     *
     * @param string $rPath
     * @return string
     */
    private function setCompileWithTemplateFile(string $rPath) : string
    {
        $cplBase = $this->compileDir . '/' . $rPath;
        $cplPath = $cplBase . '.php';
        return $cplPath;
    }

    /**
     * @param string $cplPath
     * @throws \Exception
     */
    private function includeTpl(string $cplPath)
    {
        $tplVar = $this->var['templateData']; // Do NOT REMOVE this line
        if (false == (include $cplPath)) {
            throw new TemplateFailException("No `LAYOUT` php file", "200");
        }
    }

}