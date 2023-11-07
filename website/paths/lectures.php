<?php
require_once $_SESSION['ROOTFOLDER']."paths/__init__.php";


class Lectures extends __init__
{

    function __DEFAULT__(): void
    {
        // Page settings
        $HEAD = "Lectures - Present Admin Panel";

        UTIL->validateUser();

        try {
            if (UTIL->checkPost("add-lecture")) {
                CONTROLLER->lecture($_POST['lecture'], USER->id, $_POST['batch'], $_POST['date'], $_POST['start'], $_POST['end']);
            }
        } catch (Exception $e) {
            UTIL->parseError($e);
        }

        $batches = CONTROLLER->batches();
        $lectures = CONTROLLER->lectures(USER->id);

        // Include file content
        include_once VIEWFOLDER.$this->method.".view.php";
    }
}

