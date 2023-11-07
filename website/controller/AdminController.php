<?php
require_once ROOTFOLDER . "core/Controller.php";


class AdminController extends Controller
{
    function __construct()
    {
        require_once ROOTFOLDER."model/AdminModel.php";
        $this->model = new AdminModel();
    }

    function login(string $email, string $password): array|null
    {
        return $this->getData($this->model->login($email, $password));
    }

    function signup(string $userName, string $email, string $password): ?array
    {
        return $this->getData($this->model->signup($userName, $email, $password));
    }

    function lecturers()
    {
        return $this->model->lecturers();
    }

    function batch(string $batch)
    {
        return $this->model->batch($batch);
    }

    function batches()
    {
        return $this->model->batches();
    }

    function lecture(string $lecture, int $lecturer, int $batch, string $date, string $start, string $end)
    {
        return $this->model->lecture($lecture, $lecturer, $batch, $date, $start, $end);
    }

    function lectures(int $lecturer)
    {
        return $this->model->lectures($lecturer);
    }

    function attendance(int $lecture)
    {
        return $this->model->attendance($lecture);
    }
}
