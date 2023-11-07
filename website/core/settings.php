<?php

class CoreSettings
{
    private array $info;

    function __construct() 
    {
        $this->info = array("device");
        $this->load();
    }

    function define(): array
    {
        return array(
            "device" => ""  # auto generated device id
        );
    }

    function checkSetting($settings)
    {
        if (gettype($settings) != array())
        {
            return $this->define();
        }
        foreach ($this->info as $item)
        {
            if (array_key_exists($item, $settings))
            {
                $settings[$item] = $this->define()[$item];
            }
        }
        return $settings;
    }

    function load(): void
    {
        if (!isset($_SESSION['CORE-SETTINGS'])) 
        {
            if (isset($_COOKIE['core-settings'])) 
            {
                $settings = $this->checkSetting(unserialize(base64_decode($_COOKIE['core-settings'])));
            } 
            else 
            {
                $settings = $this->define();
            }
            $_SESSION['CORE-SETTINGS'] = $settings;
        }
    }

    function set($name, $value): void
    {
        if (array_key_exists($name, $_SESSION['CORE-SETTINGS'])) 
        {
            $_SESSION['CORE-SETTINGS'][$name] = $value;
        }
    }

    function get($name) 
    {
        if (array_key_exists($name, $_SESSION['CORE-SETTINGS'])) 
        {
            return $_SESSION["CORE-SETTINGS"][$name];
        }
        return false;
    }

    function save(): void
    {
        try
        {
            unset($_COOKIE["core-settings"]);
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
        setcookie("core-settings", base64_encode(serialize($_SESSION['CORE-SETTINGS'])), time() + (10 * 365 * 24 * 60 * 60), $_SESSION['ROOTPATH']);
    }
}
