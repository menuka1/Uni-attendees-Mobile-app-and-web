<?php
require_once $_SESSION['ROOTFOLDER']."paths/__init__.php";


class Sample extends __init__
{

    function __DEFAULT__(): void
    {
        // Page settings
        $HEAD = "Sample - Sample";

        // Include file content
        include_once VIEWFOLDER.$this->method.".view.php";
    }

    function __sample__($view): void
    {
        // Page settings
        $HEAD = "Sample - Index";

        $name = $this->params[0];

        // Include file content
        include_once VIEWFOLDER.$view.".view.php";
    }
}
