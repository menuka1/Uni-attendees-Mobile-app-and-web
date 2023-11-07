<?php

class DBModel
{
    private array $types = array(
        "integer" => PDO::PARAM_INT,
        "double" => PDO::PARAM_STR,
        "float" => PDO::PARAM_STR,
        "boolean" => PDO::PARAM_BOOL,
        "string" => PDO::PARAM_STR
    );

    function execute($query)
    {
        try
        {
            $query->execute();
            return $query;
        }
        catch (PDOException $e)
        {
            if (ENVIRONMENT != "production")
            {
                UTIL->error($e, "pdo error");
            }
            return null;
        }
    }

}