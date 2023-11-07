<?php
require_once ROOTFOLDER . "core/Model.php";


class AdminModel extends Model
{
    function __construct() 
    {
        $this->API_URL = getenv("API_URL")."lecturer/";
        $this->HOST = getenv("LECTURER");
        $this->API_KEY = getenv("LECTURER_API_KEY");
    }

    function login(string $email, string $password)
    {
        $data = array(
            "email" => $email,
            "password" => $password
        );
        return $this->callAPI("login", $data);
    }

    function signup(string $userName, string $email, string $password)
    {
        $data = array(
            "user_name" => $userName,
            "email" => $email,
            "password" => $password
        );
        return $this->callAPI("signup", $data);
    }

    function lecturers()
    {
        return $this->callAPI("lecturers", array());
    }

    function batch(string $batch)
    {
        $data = array(
            "batch" => $batch
        );
        return $this->callAPI("batch", $data);
    }

    function batches()
    {
        return $this->callAPI("batches", array());
    }

    function lecture(string $lecture, int $lecturer, int $batch, string $date, string $start, string $end)
    {
        $data = array(
            "lecture" => $lecture,
            "lecturer" => $lecturer,
            "batch" => $batch,
            "date" => $date,
            "start" => $start,
            "end" => $end
        );
        return $this->callAPI("lecture", $data);
    }

    function lectures(int $lecturer)
    {
        $data = array(
            "lecturer" => $lecturer
        );
        return $this->callAPI("lectures", $data);
    }

    function attendance(int $lecture)
    {
        $data = array(
            "lecture" => $lecture
        );
        return $this->callAPI("attendance", $data);
    }
}
