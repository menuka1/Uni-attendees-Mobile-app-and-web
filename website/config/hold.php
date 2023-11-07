<?php

class Hold
{
    function __construct()
    {
        if ($_SESSION['ENVIRONMENT'] == 'maintains')
        {
            require_once $_SESSION['ROOTFOLDER'].'paths\__maintains__.php';
            $path = new __maintains__($_SESSION['ENVIRONMENT'], array());
            $path->view();
        }
        elseif ($_SESSION['ENVIRONMENT'] == 'suspended')
        {
            require_once $_SESSION['ROOTFOLDER'].'paths\__suspended__.php';
            $path = new __suspended__($_SESSION['ENVIRONMENT'], array());
            $path->view();
        }
        else
        {
            header("System error!", true, 503);
            exit(1);
        }
    }
}

new Hold();