<?php
require_once $_SESSION['ROOTFOLDER']."paths/__init__.php";


class __404__ extends __init__
{
    public array $calledParams;

    function __construct($method, $params)
    {
        try
        {
            parent::__construct($method, $params);
        }
        catch (ParseError|CompileError)
        {
            $this->method = $method;
        }
        $this->calledParams = $params;
        $this->params = array();
    }

    function __DEFAULT__(): void
    {
        // Page settings
        $HEAD = "Sample - 404";

        if (!empty($_SESSION['ERROR'])) UTIL->error($_SESSION['ERROR'], "404");

        // Include file content
        include_once VIEWFOLDER."404.view.php";
    }
}
