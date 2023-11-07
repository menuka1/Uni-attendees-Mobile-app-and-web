<?php

class Controller
{
    public Model $model;

    function getData($api_report): array|null
    {
        if ($api_report and is_object($api_report))
        {
            $api_report = (array)$api_report;
            if (array_key_exists("result", $api_report))
            {
                return array(true, $api_report['result']);
            }
            else if (array_key_exists("message", $api_report))
            {
                $error_message = ((array)$api_report)['message'];
                if (str_starts_with($error_message, "SYSTEM"))
                {
                    $_SESSION['ERROR'] = array("error" => $error_message, "page" => "404", "callback" => ROOTPATH, "data" => $_POST);
                    header("Location: " . ROOTPATH . "404");
                    exit(1);
                }
                return array(false, $error_message);
            }
            else
            {
                // System error
                $error_message = "Maintenance break.";
                $_SESSION['ERROR'] = array("error" => $error_message, "page" => "404", "callback" => ROOTPATH, "data" => $_POST);
                header("Location: " . ROOTPATH . "404");
                exit(1);
            }
        }
        else
        {
            // Something went wrong!, connection error.
            $error_message = "Something went wrong!, connection error.";
            $_SESSION['ERROR'] = array("error" => $error_message, "page" => "404", "callback" => ROOTPATH, "data" => $_POST);
            header("Location: " . ROOTPATH . "404");
            exit(1);
        }
    }
}
