<?php
// LOAD ENV
try
{
    require_once __DIR__ . "/DotEnv.php";
    (new DotEnv(__DIR__ . "/../.env"))->load();
}
catch (Exception)
{}
if (session_name() !== getenv('NAME')) session_name(getenv('NAME'));
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}

$DEVICE = getenv("DEVICE");
$LOCATION = getenv("LOCATION");

if ($DEVICE == "ON")
{
    if (!isset($_GET['nav']))
    {
        header('HTTP/1.1 500 Internal Server Error');
        exit(1);
    }

    $encodedData = $_GET['nav'];
    $decodedData = urldecode($encodedData);
    $navigator = json_decode($decodedData, true);
    $callback = (isset($_GET['callback'])) ? $_GET['callback'] : $_SESSION['ROOTPATH'];

    # DEFINE WEB SETTINGS
    include_once __DIR__ . "/settings.php";
    $settings = new CoreSettings();

    if ($settings->get("device") == "") {
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $device = sha1(uniqid('device'.$_SERVER['HTTP_USER_AGENT'], true));
        $settings->set("device", $device);
        $settings->save();
    } else {
        $device = $settings->get("device");
    }
}

require_once $_SESSION['ROOTFOLDER']."core/Util.php";
$util = new Util();

function error($callback): void
{
    if (!empty($callback))
    {
        $error_message = "Something went wrong, could not initialize device settings";
        $_SESSION['ERROR'] = array("error" => $error_message, "page" => "404", "callback" => $callback, "data" => array());
        header("Location: " . ROOTPATH . "404");
        exit(1);
    }
}

function getGEO($ip, $userAgent, $callback)
{
    $api_key = "tJAefIUnhatuFcua3NnBfeurIPuH5flTCMKR7dXuA8Mtj_gr";
    $url = "https://www.iplocinfo.com/api/v1/$ip?apiKey=$api_key&user-agent=$userAgent";
    $options = array(
        'http' => array(
            'method' => 'GET',
            'header' => "Content-type: application/json\r\n",
        )
    );

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response !== false) {
        return json_decode($response);
    } else {
        error($callback);
    }
    error($callback);
    return false;
}

if ($DEVICE == "ON")
{
    $device = $settings->get('device');
    $browser = array("name" => "DEVICE-NAME", "device" => $device, "model" => "", "os" => "", "osVersion" => "", "ua" => "", "browser" => array("name" => "", "version" => ""), "engineName" => "", "engineVersion" => "", "vendor" => "", "memory" => "", "network" => "", "cores" => "", "max_tp" => "", "touch_screen" => "", "platform" => "", "isMobile" => "", "isTablet" => "", "isBrowser" => "", "isSmartTV" => false);
}
if ($LOCATION == "ON")
{
    $geo = array("zipcode" => "", "capital" => "", "city" => "", "continent" => "", "continent_code" => "", "country" => "", "country_code" => "", "emoji" => "", "latitude" => "", "longitude" => "", "numeric_code" => "", "phone_code" => "", "region" => "", "domain" => "");
}
    
// Get client IP address from the appropriate header
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
{
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
elseif (!empty($_SERVER['HTTP_CLIENT_IP']))
{
    $ip = $_SERVER['HTTP_CLIENT_IP'];
}
else
{
    $ip = $_SERVER['REMOTE_ADDR'];
}

if ($DEVICE == "ON")
{
    $userAgent = $navigator['userAgent']??$_SERVER['HTTP_USER_AGENT'];

    $browser['ua'] = $userAgent;
    $browser['vendor'] = (isset($navigator['vendor'])) ? $navigator['vendor'] : "";
    $browser['memory'] = (isset($navigator['deviceMemory'])) ? $navigator['deviceMemory'] : "";
    $browser['network'] = (isset($navigator['effectiveType'])) ? $navigator['effectiveType'] : "";
    $browser['cores'] = (isset($navigator['hardwareConcurrency'])) ? $navigator['hardwareConcurrency'] : "";
    $browser['max_tp'] = (isset($navigator['maxTouchPoints'])) ? $navigator['maxTouchPoints'] : "";
    $browser['platform'] = (isset($navigator['platform'])) ? $navigator['platform'] : "";
}

if ($LOCATION == "ON" && getenv("ENVIRONMENT") == "production")
{
    $data = getGEO($ip, $_SERVER['HTTP_USER_AGENT'], $callback??$_SESSION['ROOTPATH']);
}
elseif ($LOCATION == "ON")
{
    $data = array("ip_data" => array("location" => array("area" => 65610, "capital" => "Colombo", "city" => "Nugegoda", "continent_code" => "AS", "continent_name" => "Asia", "country" => "Sri Lanka", "country_code2" => "LK", "emoji" => "LK", "latitude" => 6.871900000000011, "longitude" => 79.89389999999997, "numericCode" => "144", "phone_code" => array('94'), "subregion" => "Southern Asia", "topLevelDomain" => array('.lk'))), "user_agent" => array("browser" => "Edge", "browser_version" => "110.0.1587", "device_family" => "Other", "is_mobile" => false, "is_pc" => true, "is_tablet" => false, "is_touch_screen" => false, "os_family" => "Windows", "os_version" => "10"));
}

if ($DEVICE == "ON")
{
    $browser['browser']['name'] = $data['user_agent']['browser'];
    $browser['browser']['version'] = $data['user_agent']['browser_version'];
    $browser['model'] = ($data['user_agent']['device_family'] != "Other") ? $data['user_agent']['device_family'] : "";
    $browser['touch_screen'] = $data['user_agent']['is_touch_screen'];
    $browser['os'] = $data['user_agent']['os_family'];
    $browser['osVersion'] = $data['user_agent']['os_version'];
    $browser['isMobile'] = $data['user_agent']['is_mobile'];
    $browser['isTablet'] = $data['user_agent']['is_tablet'];
    $browser['isBrowser'] = $data['user_agent']['is_tablet'];
    $browser['isBrowser'] = $data['user_agent']['is_pc'];
}

if ($LOCATION == "ON")
{
    $geo['zipcode'] = $data['ip_data']['location']['area'];
    $geo['capital'] = $data['ip_data']['location']['capital'];
    $geo['city'] = $data['ip_data']['location']['city'];
    $geo['continent'] = $data['ip_data']['location']['continent_name'];
    $geo['continent_code'] = $data['ip_data']['location']['continent_code'];
    $geo['country'] = $data['ip_data']['location']['country'];
    $geo['country_code'] = $data['ip_data']['location']['country_code2'];
    $geo['emoji'] = $data['ip_data']['location']['emoji'];
    $geo['latitude'] = $data['ip_data']['location']['latitude'];
    $geo['longitude'] = $data['ip_data']['location']['longitude'];
    $geo['numeric_code'] = $data['ip_data']['location']['numericCode'];
    $geo['phone_code'] = $data['ip_data']['location']['phone_code'][0];
    $geo['region'] = $data['ip_data']['location']['subregion'];
    $geo['domain'] = $data['ip_data']['location']['topLevelDomain'][0];
}

if ($DEVICE == "ON") $_SESSION['DEVICE'] = $browser;
if ($LOCATION == "ON") $_SESSION['LOCATION'] = $geo;
$_SESSION['IP'] = $ip;

if ($DEVICE == "ON")
{
    header("Location: ".$_SESSION['ROOTPATH']."?callback=".($callback??$_SESSION['ROOTPATH']));
    exit(1);
}
