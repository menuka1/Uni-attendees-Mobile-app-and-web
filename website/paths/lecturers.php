<?php
require_once $_SESSION['ROOTFOLDER']."paths/__init__.php";


class Lecturers extends __init__
{

    function __DEFAULT__(): void
    {
        // Page settings
        $HEAD = "Lecturers - Present Admin Panel";

        UTIL->validateUser();

        try {
            if (UTIL->checkPost("add-lecturer") && UTIL->confirmPostPassword("password", $_POST['confirm'])) {
                CONTROLLER->signup($_POST['lecturer'], $_POST['email'], $_POST['password']);
            }
        } catch (Exception $e) {
            UTIL->parseError($e);
        }

        $lecturers = CONTROLLER->lecturers();

        // Include file content
        include_once VIEWFOLDER.$this->method.".view.php";
    }
}
