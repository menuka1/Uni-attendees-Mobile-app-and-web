<?php
require_once $_SESSION['ROOTFOLDER']."paths/__init__.php";


class Auth extends __init__
{

    function __DEFAULT__(): void
    {
        // Page settings
        $HEAD = "Authentication - Present Admin Panel";

        UTIL->header("auth/");

        // Include file content
        include_once VIEWFOLDER.$this->method.".view.php";
    }

    function index($view): void
    {
        // Page settings
        $HEAD = "Authentication - Present Admin Panel";

        UTIL->checkUser();

        if (UTIL->checkPost("login")) {
            try {
                $result = CONTROLLER->login($_POST['email'], $_POST['password']);

                if ($result[0] === true) {
                    $_SESSION['USER'] = $result[1];
                    UTIL->header();
                } else {
                    throw new Exception("login:Failed to log in.");
                }
            } catch (Exception $e) {
                UTIL->parseError($e);
            }
        }

        // Include file content
        include_once VIEWFOLDER.$view.".view.php";
    }

    function logout($view): void
    {
        unset($_SESSION['USER']);
        UTIL->header("auth/");
    }
}
