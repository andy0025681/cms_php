<?php

namespace controller;
use \framework\Tpl;

class Controller extends Tpl
{
    function __construct()
    {
        $config = $GLOBALS['config'];
        parent::__construct($config['TPL_VIEW'], $config['TPL_CACHE']);
    }

    // 重寫父類display方法，調用display方法時如果沒有指定模板，就使用函數名模塊名拼接模板
    function display($viewName = null, $isInclude = true, $uri = null)
    {
        if(empty($viewName)) {
            $viewName = $_GET['m'].'/'.$_GET['a'].'.html';
        }
        parent::display($viewName, $isInclude, $uri);
    }
}
