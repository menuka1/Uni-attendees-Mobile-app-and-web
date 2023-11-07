<?php

/**
 * The App class is the entry point for a simple MVC web application.
 */
class App 
{
    /**
     * The name of the default controller to use if one is not specified in the URL.
     * @var object
     */
    protected object $paths;

    /**
     * The name of the default method to call if one is not specified in the URL.
     * @var string
     */
    protected string $method = "home";

    /**
     * An array of parameters passed in the URL.
     * @var array
     */
    protected array $params = array();

    /**
     * The constructor method for the App class.
     * This method is responsible for mapping the URL entered by the user to the appropriate controller and method.
     */
    public function __construct() 
    {
        // Get the URL segments from the user input.
        $URL = $this->getURL();

        $this->method = strtolower($URL[0]);
        unset($URL[0]); // Remove the controller name from the URL array.

        // Include the controller file and create the controller object.
        require $_SESSION['ROOTFOLDER']."config/paths.php";
        $this->paths = new Paths();

        // Set the URL parameters.
        $URL = array_values($URL);
        $this->params = $URL;
        
        $this->paths->view($this->method, $this->params);
    }

    /**
     * Get the URL segments from the user input.
     * @return array An array of URL segments.
     */
    private function getURL(): array
    {
        // Get the URL from the user input or default to 'home'.
        $url = $_GET['url'] ?? "home";
        // Sanitize the URL and return as an array of segments.
        $view = explode("/", filter_var(trim($url, "/")), FILTER_SANITIZE_URL);
        // Define index if required
        if (str_ends_with($url, '/')) {
            $view[] = 'index';
        }
        return $view;
    }
}

$app = new App();
