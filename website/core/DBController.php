<?php
require_once ROOTFOLDER."core/DBConnection.php";


class DBController
{
    public PDO $con;
    public DBModel $model;

    function __construct()
    {
        $db = new DBConnection();
        $this->con = $db->connect();
    }

}