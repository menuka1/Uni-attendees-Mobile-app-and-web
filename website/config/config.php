<?php 

global $_POST;

// SAVE POST INTO SESSION
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $destinationFolder = $_SESSION['ROOTFOLDER'].'statics/tmp/';
    foreach ($_FILES as $name => $file) {
        for ($i=0; $i < count($file['name']); $i++)
        {
            $newFileName = basename($file['tmp_name'][$i]);
            move_uploaded_file($file['tmp_name'][$i], $destinationFolder . $newFileName);
            $_FILES[$name]['tmp_name'][$i] = $destinationFolder . $newFileName;
        }
    }

    $_SESSION['post_data'] = $_POST;
    unset($_POST);
    $_SESSION['file_data'] = $_FILES;
    unset($_FILES);
    header("Location: ".$_SERVER['REQUEST_URI']);
    exit(1);
}

// DEFNE ROOT
$root = getenv('ROOT_NAME');

// Check if the environment is set up
if (!UTIL->checkENV())
{
    $callback = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: $root?callback=".base64_encode(serialize($callback)));
    exit(1);
}

// DEFINE PATHS
if (!defined("ENVIRONMENT")) define("ENVIRONMENT", getenv('ENVIRONMENT'));
if (!defined("ROOTPATH")) define("ROOTPATH", $_SESSION['ROOTPATH']);
if (!defined("ROOTFOLDER")) define("ROOTFOLDER", $_SESSION['ROOTFOLDER']);
if (!defined("APPPATH")) define("APPPATH", $_SESSION['APPPATH']);
if (!defined("APPFOLDER")) define("APPFOLDER", $_SESSION['APPFOLDER']);
if (!defined("VIEWPATH")) define("VIEWPATH", $_SESSION['VIEWPATH']);
if (!defined("VIEWFOLDER")) define("VIEWFOLDER", $_SESSION['VIEWFOLDER']);
if (!defined("STATICS")) define("STATICS", $_SESSION['STATICS']);
if (!defined("DEVICE")) if (getenv('DEVICE') == "ON" && isset($_SESSION['DEVICE'])) define("DEVICE", $_SESSION['DEVICE']);
if (!defined("LOCATION")) if (getenv('LOCATION') == "ON" && isset($_SESSION['LOCATION'])) define("LOCATION", $_SESSION['LOCATION']);
if (!defined("IP")) if (isset($_SESSION['IP'])) define("IP", $_SESSION['IP']);

// SETUP ENVIRONMENT SETTINGS
UTIL->setupENV();

// DEFINE USER
if (ISSET($_SESSION['USER'])) 
{
    define("USER", $_SESSION['USER']);
}

// RECREATE POST
if (@$_SESSION['post_data'])
{
    $_POST = $_SESSION['post_data'];
    unset($_SESSION['post_data']);
    $_FILES = $_SESSION['file_data'];
    unset($_SESSION['file_data']);
}
