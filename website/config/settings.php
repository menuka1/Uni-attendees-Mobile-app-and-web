<?php

class Settings 
{

    private array $info;

    function __construct() 
    {
        $this->info = array();
        $this->load();
    }

    function define(): array
    {
        return array(
        );
    }

    function checkSetting($settings): array
    {
        if (!is_array($settings))
        {
            return $this->define();
        }
        foreach ($this->info as $item)
        {
            if (!array_key_exists($item, $settings))
            {
                $settings[$item] = $this->define()[$item];
            }
        }
        return $settings;
    }

    function load(): void
    {
        if (!isset($_SESSION['SETTINGS'])) 
        {
            if (isset($_COOKIE['settings'])) 
            {
                $settings = $this->checkSetting(unserialize(base64_decode($_COOKIE['settings'])));
            } 
            else 
            {
                $settings = $this->define();
            }
            $_SESSION['SETTINGS'] = $settings;
        }
    }

    function set($name, $value): void
    {
        if (array_key_exists($name, $_SESSION['SETTINGS']))
        {
            $_SESSION['SETTINGS'][$name] = $value;
        }
    }

    function get($name)
    {
        if (array_key_exists($name, $_SESSION['SETTINGS'])) 
        {
            return $_SESSION["SETTINGS"][$name];
        }
        return null;
    }

    function save(): void
    {
        try
        {
            unset($_COOKIE["settings"]);
        }
        catch (Exception $e)
        {
            if (ENVIRONMENT != "production")
            {
                UTIL->error($e, "settings save error");
            }
            else
            {
                header("Server error!", true, 503);
            }
            exit(1);
        }
        setcookie("settings", base64_encode(serialize($_SESSION['SETTINGS'])), time() + (10 * 365 * 24 * 60 * 60), ROOTPATH);
    }
}
