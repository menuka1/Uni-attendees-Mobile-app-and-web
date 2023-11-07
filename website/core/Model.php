<?php

class Model
{
    public string $API_URL;
    public string $HOST;
    public string $API_KEY;

    function callAPI($action, $data)
    {
        try
        {
            $data['host'] = $this->HOST;
            $data['api_key'] = $this->API_KEY;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->API_URL . str_replace(' ', '%20', $action),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array("cache-control: no-cache", "Content-type: application/json")
            ));
            $api_report = json_decode(curl_exec($curl));
            $err = curl_error($curl);
            if (!empty($err) && ENVIRONMENT != "production")
            {
                UTIL->error($err, "API CALL ERROR");
            }
            curl_close($curl);

            return $api_report;
        }
        catch (Exception $e)
        {
            if (ENVIRONMENT != "production")
            {
                $error_message = "API Connection Error: ".$e;
                UTIL->error($error_message, "API");
            }
            return false;
        }
    }
}
