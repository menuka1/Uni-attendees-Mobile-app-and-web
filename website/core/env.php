<?php

// DEFNE ROOT
$root = getenv('ROOT_NAME');

// Callback if no callback in get
if (!isset($_GET['callback']))
{
    $callback = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $root = UTIL->replaceBaseUrl($root, UTIL->extractBaseUrl($callback));
    header("Location: $root?callback=".base64_encode(serialize($callback)));
    exit(1);
}

// DEFINE CALLBACK PATH
$callback = (!empty($_GET['callback'])) ? unserialize(base64_decode($_GET['callback'])) : $root;

// 'DEFINE' FUNCTION FOR PROPER PATH DEFINITION
function DEF($path): false|string
{
    if (is_dir($path)) 
    {
        if (($_temp = realpath($path)) !== FALSE) 
        {
            $path = $_temp;
        }
        return $path.DIRECTORY_SEPARATOR;
    }
    return false;
}

// DEFINE ENVIRONMENT
define("ENVIRONMENT", getenv('ENVIRONMENT'));

### SET DEFAULT SETTINGS BASED ON ENVIRONMENT ###

// SET TIME FORMAT
if( ! ini_get('date.timezone') ) 
{ 
    date_default_timezone_set('GMT'); 
}

// CHECK ENVIRONMENT
UTIL->setupENV();

### END OF ENVIRONMENT SETTING ###


### SETUP USER ###


### SETUP PATHS ###

if (UTIL->onWorkENV()) $root = UTIL->replaceBaseUrl($root, UTIL->extractBaseUrl($callback));

$root_folder = getenv('ROOT_FOLDER');
define("ROOTPATH", $root);
define("ROOTFOLDER", DEF($root_folder));

// SET PATHS BASED ON USER CHARACTER SETTINGS
$system_path = "";
$application_path = "controller/";
$view_path = "view/";
$statics_path = "statics/";

// DEFINE APP
define("APPPATH", ROOTPATH.$system_path.$application_path);
define("APPFOLDER", DEF(ROOTFOLDER.$application_path));

// DEFINE VIEW
define("VIEWPATH", ROOTPATH.$system_path.$view_path);
define("VIEWFOLDER", DEF(ROOTFOLDER.$view_path));

// DEFINE STATIC
define("STATICS", ROOTPATH.$system_path.$statics_path);

// SAVE PATHS TO SESSION
$_SESSION['ROOTPATH'] = ROOTPATH;
$_SESSION['ROOTFOLDER'] = ROOTFOLDER;
$_SESSION['APPPATH'] = APPPATH;
$_SESSION['APPFOLDER'] = APPFOLDER;
$_SESSION['VIEWPATH'] = VIEWPATH;
$_SESSION['VIEWFOLDER'] = VIEWFOLDER;
$_SESSION['STATICS'] = STATICS;

### SETUP PATHS ###


### SETUP SOCIAL ###

if (!isset($_SESSION['TRACK']) && getenv('TRACK') == "ON")
{
    include_once 'core/track.php';
}

### END SOCIAL ###

### SET UP DEVICE SETTING ###

if (!isset($_SESSION['DEVICE']) && getenv("device") == "ON")
{
    header("Location: ".ROOTPATH."core/get-browser.php?callback=".base64_encode(serialize($callback)));
    exit(1);
}

if (!isset($_SESSION['LOCATION']) && getenv("LOCATION") == "ON")
{
    require_once "set-browser.php";
}

### END OF DEVICE SETTING ###

$_SESSION['SETUP_ENV'] = true;

### START CALLBACK ###

// HEAD BACK WHERE BELONG
// header("Location: ".$callback);
// exit(1);

### START CALLBACK ###

// Load page
require_once ROOTFOLDER."core/autoload.php";

### END CALLBACK ###
