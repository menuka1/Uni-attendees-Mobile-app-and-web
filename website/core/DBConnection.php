<?php

class DBConnection{
    private string $SERVER;
    private string $DB;
    private string $USERNAME;
    private string $PASSWORD;

    function __construct()
    {
        $this->SERVER = getenv('DB_HOST');
        $this->DB = getenv('DB_SCHEMA');
        $this->USERNAME = getenv('DB_USERNAME');
        $this->PASSWORD = getenv('DB_PASSWORD');
    }

    function connect(): PDO
    {
        return new PDO("mysql:host=$this->SERVER;dbname=$this->DB", $this->USERNAME, $this->PASSWORD);
    }
}
