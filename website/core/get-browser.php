<?php
// LOAD ENV
require_once __DIR__ ."/DotEnv.php";
(new DotEnv(__DIR__ . "/../.env"))->load();
if (session_name() !== getenv('NAME')) session_name(getenv('NAME'));
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}

$callback = $_GET['callback'];

?>
<script>
    try
    {
        // Define the properties to retrieve from navigator
        const navigatorProperties = ['userAgent', 'vendor', 'deviceMemory', 'connection', 'hardwareConcurrency', 'maxTouchPoints', 'platform'];
        // Create an object to store the property values
        let navigatorData = {};
        // Retrieve the property values from navigator
        for (let i = 0; i < navigatorProperties.length; i++)
        {
            let propertyName = navigatorProperties[i];
            if (propertyName in window.navigator) 
            {
                navigatorData[propertyName] = window.navigator[propertyName];
            }
        }
        // Check if the connection property exists in the navigator object
        if ('connection' in window.navigator) 
        {
            let connection = window.navigator.connection;

            // Check if the effectiveType property exists in the connection object
            if ('effectiveType' in connection) 
            {
                navigatorData['effectiveType'] = connection.effectiveType;
            }
        }

        let encodedData = encodeURIComponent(JSON.stringify(navigatorData));
        let callback = "<?=$_GET['callback'] ?? ''?>";
        let url = "<?=$_SESSION['ROOTPATH']?>core/set-browser.php?nav=" + encodedData + "&callback=" + callback;
        window.location.assign(url);
    } 
    catch (error)
    {
        console.log(error);
        window.location.assign("<?=$_SESSION['ROOTPATH']?>404");
    }
</script>