<?php

// LOAD ENV
require_once __DIR__ ."/core/DotEnv.php";
(new DotEnv(__DIR__ . "/.env"))->load();

// START SESSION
if (session_name() !== getenv('NAME')) session_name(getenv('NAME'));

if (session_status() === PHP_SESSION_NONE) 
{ 
    session_start(); 
}

require_once __DIR__."/core/Util.php";
$util = new Util();
define("UTIL", $util);


if ($util->checkENV()) 
{
    ### START CALLBACK ###

    if (isset($_GET['callback'])) {
        // DEFINE CALLBACK PATH
        $callback = (!empty($_GET['callback'])) ? unserialize(base64_decode($_GET['callback'])) : $_SESSION['ROOTPATH'];

        // HEAD BACK WHERE BELONG
        header("Location: ".$callback);
        exit(1);
    }

    ### END CALLBACK ###

    // Load page
    require_once __DIR__."/core/autoload.php";
}
else
{
    require_once __DIR__."/core/env.php";
}
