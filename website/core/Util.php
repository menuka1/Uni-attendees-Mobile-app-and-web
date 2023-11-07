<?php

class Util 
{
    private string $key = "";
    private string $error = "";

    function __construct() 
    {
        $this->initProcesses();
    }

    function checkENV(): bool
    {
        // Handle maintains break & suspensions
        if (isset($_SESSION['SETUP_ENV']) && $_SESSION['SETUP_ENV'] && (getenv('ENVIRONMENT') == 'maintains' || getenv('ENVIRONMENT') == 'suspended'))
        {
            require_once $_SESSION['ROOTFOLDER'] . 'config/hold.php';
        }
        return isset($_SESSION['SETUP_ENV']) && $_SESSION['SETUP_ENV'] == true;
    }

    function checkTrack(): bool
    {
        return (getenv('TRACK')=='ON');
    }

    function checkDevice($device): bool
    {
        return true;
    }

    function checkLocation($location): bool
    {
        return true;
    }

    function checkIP($ip): bool
    {
        return true;
    }

    function print($data, $name="printing"): void
    {
        echo "[".strtoupper($name)."]";
        echo "<pre style='background:black; color: green;'>";
        echo print_r($data, 1);
        echo "</pre>";
    }

    function error($data, $name="error"): void
    {
        echo "[".strtoupper($name)."]";
        echo "<pre style='background:black; color: red;'>";
        echo print_r($data, 1);
        echo "</pre>";
    }

    function setupENV(): void
    {
        switch (ENVIRONMENT)
        {
            case 'development':
                error_reporting(-1);
                ini_set('display_errors', 1);
                break;

            case 'testing':
            case 'maintains':
            case 'suspended':
            case 'production':
                ini_set('display_errors', 0);
                if (version_compare(PHP_VERSION, '5.3', '>=')) {
                    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
                } else {
                    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
                }
                break;

            default:
                header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
                echo 'The application environment is not set correctly.';
                exit(1); // EXIT_ERROR
        }
    }

    function uploadFiles($files, $targetDirectory): false | array
    {
        for($i = 0; $i < count($files['name']); $i++) {
            if($files['error'][$i] === 0) { // If file was uploaded successfully...
                $tmpName = $files['tmp_name'][$i];
                $originalName = $files['name'][$i];
                $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
                $newName = sha1(time() . $originalName) . "." . $fileExtension;
                $targetPath = $targetDirectory . $newName;
                if (rename(realpath($tmpName), $targetPath)) {
                    $files['new_name'] = $newName;
                } else {
                    $this->error("Failed to move file to target directory.", "file upload failed");
                    return false;
                }
            } else {
                $this->error("File upload error.", "file upload error");
                return false;
            }
        }
        return $files;
    }

    function checkPost($key, $value="true"): bool
    {
        return (isset($_POST[$key]) && $_POST[$key] == $value);
    }

    function checkGet($key, $value="true"): bool
    {
        return (isset($_GET[$key]) && $_GET[$key] == $value);
    }

    function getExists($key): bool
    {
        return (!empty($_GET[$key]));
    }

    function postExists($key): bool
    {
        return (!empty($_POST[$key]));
    }

    function checkSettings($key, $value=true): bool
    {
        return SETTINGS->get($key) == $value;
    }

    function header($to=""): void
    {
        if (!empty($to) || $to == "")
        {
            header("Location: " . ROOTPATH . $to);
            exit(1);
        }
    }

    function sendBack(): void
    {
        $exceptions = array();
        $previousPage = $_SERVER['HTTP_REFERER'] ?? ROOTPATH;
        foreach ($exceptions as $exception)
        {
            if (str_starts_with($previousPage, ROOTPATH.$exception)) {
                $previousPage = ROOTPATH;
                break;
            }
        }
        if (!empty($previousPage))
        {
            header('Location: ' . $previousPage);
            exit(1);
        }
    }

    function extractBaseUrl($url): string
    {
        $parsedUrl = parse_url($url);
        $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $host = $parsedUrl['host'] ?? '';
        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        return $scheme . $host . $port;
    }

    function replaceBaseUrl($originalUrl, $newBaseUrl): string
    {
        $originalComponents = parse_url($originalUrl);
        $newComponents = parse_url($newBaseUrl);
        $scheme = isset($newComponents['scheme']) ? $newComponents['scheme'] . '://' : $originalComponents['scheme'] . '://';
        $host = $newComponents['host'] ?? $originalComponents['host'];
        $port = isset($newComponents['port']) ? ':' . $newComponents['port'] : (isset($originalComponents['port']) ? ':' . $originalComponents['port'] : '');
        $path = $originalComponents['path'] ?? '';
        $query = isset($originalComponents['query']) ? '?' . $originalComponents['query'] : '';
        $fragment = isset($originalComponents['fragment']) ? '#' . $originalComponents['fragment'] : '';
        return $scheme . $host . $port . $path . $query . $fragment;
    }

    function onWorkENV(): bool
    {
        return defined('ENVIRONMENT') && (ENVIRONMENT == 'development' || ENVIRONMENT == 'testing');
    }

    function initProcesses(): void
    {
        if (!isset($_SESSION['PROCESSES']))
        {
            $_SESSION['PROCESSES'] = array();
        }
    }

    function processExists($process)
    {
        if (isset($_SESSION['PROCESSES'][$process]))
        {
            $process += 1;
            return $this->processExists($process);
        }
        return $process;
    }

    function checkProcess($process): bool
    {
        return (isset($_SESSION['PROCESSES'][$process]));
    }

    function createProcess($data=array()): string
    {
        $processId = sha1($this->processExists(time()));
        $_SESSION['PROCESSES'][$processId] = $data;
        return $processId;
    }

    function setProcess($process, $data=array()): bool
    {
        if ($this->checkProcess($process))
        {
            $_SESSION['PROCESSES'][$process] = $data;
            return true;
        }
        if (ENVIRONMENT != "production")
        {
            UTIL->error("Process key does not exists.", "process");
        }
        return false;
    }

    function getProcess($process)
    {
        if ($this->checkProcess($process))
        {
            return $_SESSION['PROCESSES'][$process];
        }
        if (ENVIRONMENT != "production")
        {
            $this->error("Process key does not exists.");
        }
        return false;
    }

    function removeProcess($process): bool
    {
        if ($this->checkProcess($process))
        {
            unset($_SESSION['PROCESSES'][$process]);
            return true;
        }
        if (ENVIRONMENT != "production")
        {
            $this->error("Process key does not exists.");
        }
        return false;
    }

    function checkPID($header=""): bool
    {
        if ($this->getExists("pid"))
        {
            $pid = $_GET["pid"];
            if ($this->checkProcess($pid))
            {
                return true;
            }
        }
        $this->header($header);
        return false;
    }

    /**
     * @throws Exception
     */
    function validatePostName(string $key, string $call="Name"): string
    {
        if (empty($_POST[$key]))
        {
            throw new Exception("$key : $call not provided.");
        }
        $name = trim($_POST[$key]);
        if (!preg_match('/^[a-zA-Z ]+$/', $name))
        {
            throw new Exception("$key : $call should contain only letters and spaces.");
        }
        if (strlen($name) < 2)
        {
            throw new Exception("$key : $call should have a minimum length of 2 characters.");
        }
        return $name;
    }

    /**
     * @throws Exception
     */
    function validatePostGmail(string $key, string $call="Email address"): string
    {
        if (empty($_POST[$key]))
        {
            throw new Exception("$key : $call not provided.");
        }
        $email = trim($_POST[$key]);
        if (!preg_match('/^[a-z0-9._%+-]+@gmail\.com$/', $email))
        {
            throw new Exception("$key : $call must end with @gmail.com");
        }
        return $email;
    }

    /**
     * @throws Exception
     */
    function validatePostPassword($key, $call = "Password"): string
    {
        if (empty($_POST[$key]))
        {
            throw new Exception("$key : $call not provided.");
        }
        $password = trim($_POST[$key]);
        $minLength = 8;
        $maxLength = 20;
        $hasUppercase = preg_match('/[A-Z]/', $password);
        $hasLowercase = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/\d/', $password);
        $hasSpecialChar = preg_match('/[!@#$%^&*()\-_=+{}[\]|;:\'",.<>\/?~]/', $password);
        if (strlen($password) < $minLength || strlen($password) > $maxLength)
        {
            throw new Exception("$key : $call should have a length between $minLength and $maxLength characters.");
        }
        if (!$hasUppercase || !$hasLowercase || !$hasNumber || !$hasSpecialChar)
        {
            throw new Exception("$key : $call should meet the required criteria: at least one uppercase letter, one lowercase letter, one digit, and one special character.");
        }
        return $password;
    }


    /**
     * @throws Exception
     */
    function confirmPostPassword(string $key, string $password): string
    {
        if (empty($_POST[$key]) || trim($_POST[$key]) != $password)
        {
            throw new Exception("$key : Passwords are not equal.");
        }
        return $_POST[$key];
    }

    /**
     * @throws Exception
     */
    function validatePostCountryCode($key, $countryCodes, $call="Country code"): array
    {
        if (empty($_POST[$key]))
        {
            throw new Exception("$key : $call not provided.");
        }
        $country_code = trim($_POST[$key]);
        if (!preg_match('/^\s*\w+(?:\s+\w+)*\s+\(\+\d+\)\s*$/', $country_code))
        {
            throw new Exception("$key : $call is invalid.");
        }
        preg_match('/^\s*([^+]+)\s*\(\s*\+([^)]+)\s*\)\s*$/', trim($_POST['country_code']), $code_of_country);
        $country = trim($code_of_country[1]);
        $code = trim($code_of_country[2]);
        if (!array_key_exists($country, $countryCodes))
        {
            throw new Exception("$key : $call is invalid.");
        }
        if ($countryCodes[$country] !== $code)
        {
            throw new Exception("$key : $call is invalid.");
        }
        return array($country, $code);
    }

    /**
     * @throws Exception
     */
    function validatePostMobileNumber($key, $length, $call="Mobile number"): int
    {
        if (empty($_POST[$key]))
        {
            throw new Exception("$key : $call not provided.");
        }
        $mobile_number = trim($_POST[$key]);
        if (!is_numeric($mobile_number))
        {
            throw new Exception("$key : $call is invalid.");
        }
        $mobileNumber = (int)$mobile_number;
        if (strlen((string)$mobileNumber) !== $length && strlen($mobile_number) !== $length)
        {
            throw new Exception("$key : $call must contain $length digits.");
        }
        return $mobileNumber;
    }

    /**
     * @throws Exception
     */
    function validatePostBirthday($key, $call="Date of birth"): string
    {
        if (empty($_POST[$key]))
        {
            throw new Exception("$key : $call is not provided.");
        }
        $birthday = trim($_POST[$key]);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday))
        {
            throw new Exception("$key : $call is invalid.");
        }
        list($year, $month, $day) = explode('-', $birthday);
        if (!checkdate($month, $day, $year))
        {
            throw new Exception("$key : $call is invalid.");
        }
        $today = date('Y-m-d');
        if ($birthday >= $today)
        {
            throw new Exception("$key : $call is invalid.");
        }
        return $birthday;
    }

    /**
     * @throws Exception
     */
    function validatePostGender($key, $call="Gender"): int
    {
        if (empty($_POST[$key]))
        {
            throw new Exception("$key : $call is not provided.");
        }
        $gender = trim($_POST[$key]);
        if (!is_numeric($gender))
        {
            throw new Exception("$key : $call is invalid.");
        }
        $gender = (int)$gender;
        if ($gender!==0 && $gender!==1)
        {
            throw new Exception("$key : $call is invalid.");
        }
        return $gender;
    }

    function checkUser(): void
    {
        if (defined("USER")) {
            $this->header();
        }
    }

    function validateUser(): void
    {
        if (!defined("USER")) {
            $this->header("auth/");
        }
    }

    function parseError(Exception $e): void
    {
        $error_message = explode(":", $e->getMessage(), 2);
        $this->key = trim($error_message[0]);
        $this->error = trim($error_message[1]);
    }

    function promptError(string $key): string
    {
        return (!empty($this->key) && !empty($key) && $this->key == $key) ? $this->error : "";
    }
}
