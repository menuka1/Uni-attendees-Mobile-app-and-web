<?php
require_once $_SESSION['ROOTFOLDER']."paths/__init__.php";


class __maintains__ extends __init__
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
        $HEAD = "Sample - maintains";

        // Code here

        // Include file content
        include_once VIEWFOLDER."maintains.view.php";
    }
}
