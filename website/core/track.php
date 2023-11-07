<?php 

$utm = array(
    "UTM_SOURCE" => false,
    "UTM_MEDIUM" => false,
    "UTM_CAMPAIGN" => false,
    "UTM_CONTENT" => false,
    "FBCLID" => false,
    "GCLID" => false,
    "RETARTGET" => false,
    "RECENT" => false
);

if (isset($_REQUEST['utm_source']))
{
    $utm['UTM_SOURCE'] = $_REQUEST['utm_source'];
}
if (isset($_REQUEST['utm_medium']))
{
    $utm['UTM_MEDIUM'] = $_REQUEST['utm_medium'];
}
if (isset($_REQUEST['utm_campaign']))
{
    $utm['UTM_CAMPAIGN'] = $_REQUEST['utm_campaign'];
}
if (isset($_REQUEST['utm_content']))
{
    $utm['UTM_CONTENT'] = $_REQUEST['utm_content'];
}
if (isset($_REQUEST['fbclid']))
{
    $utm['FBCLID'] = $_REQUEST['fbclid'];
}
if (isset($_REQUEST['gclid']))
{
    $utm['GCLID'] = $_REQUEST['gclid'];
}
if (isset($_REQUEST['retarget']))
{
    $utm['RETARGET'] = $_REQUEST['retarget'];
}
$utm['PARAMS'] = $_SERVER['QUERY_STRING'];

$_SESSION['UTM'] = $utm;
$_SESSION['UTM_STR'] = implode($utm);
