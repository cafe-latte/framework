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
class ModelView
{
    public $modelVar = [];
    public $template = [];

    /**
     * ModelView constructor.
     */
    public function __construct()
    {
    }

    /**
     * to add view data
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function addViewData($key, $value): ModelView
    {
        if ($key) {
            $this->modelVar[$key] = $value;
        } else {
            $this->modelVar = $value;
        }

        return $this;
    }

    /**
     *
     *
     * @param array $arg
     * @return $this
     */
    public function setViewLayout(array $arg): ModelView
    {
        foreach ($arg as $fid => $htmlFileName) {
            $this->template[$fid] = $htmlFileName;
        }
        return $this;
    }

    /**
     * to get view data for template
     *
     */
    public function getViewData()
    {
        return $this->modelVar;
    }

    /**
     * to get layout data for template
     *
     * @return array
     */
    public function getViewLayout(): array
    {
        return $this->template;
    }
}
