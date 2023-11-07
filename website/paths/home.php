<?php
require_once $_SESSION['ROOTFOLDER']."paths/__init__.php";


class Home extends __init__
{


    function __DEFAULT__(): void
    {
        // Page settings
        $HEAD = "Dashboard - Present Admin Panel";

        UTIL->validateUser();

        try {
            if (UTIL->getExists("lecture") && UTIL->getExists("batch")) {
                $selectedLecture = (int)$_GET['lecture'];
                $selectedBatch = (int)$_GET['batch'];

                $attendance = CONTROLLER->attendance($selectedLecture);
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
