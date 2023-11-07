<?php
require_once $_SESSION['ROOTFOLDER']."paths/__init__.php";


class Batches extends __init__
{

    function __DEFAULT__(): void
    {
        // Page settings
        $HEAD = "Batches - Present Admin Panel";

        UTIL->validateUser();

        try {
            if (UTIL->checkPost("create-batch")) {
                CONTROLLER->batch($_POST['batch']);
            }
        } catch (Exception $e) {
            UTIL->parseError($e);
        }

        $batches = CONTROLLER->batches();

        // Include file content
        include_once VIEWFOLDER.$this->method.".view.php";
    }
}
