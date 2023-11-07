<?php


class __init__
{
    public mixed $method;
    public mixed $params;
    public int $page;
    public function __DEFAULT__() {}

    /**
     * Function constructor -> set up the page settings
     * @param $method - main method of the path
     * @param $params - extended paths
     * @return void
     */
    function __construct($method, $params)
    {
        $this->method = $method;
        $this->params = $params;
        $this->page = 0;

        // Configure basic requirements
        $this->config();
    }

    /**
     * Get the function name from the view path
     * @param $view - view path we are working on
     * @return string - name of the function name
     */
    private function getFunc($view): string
    {
        return str_replace('/', '_', (str_replace('-', '', $view)));
    }

    /**
     * Get expanded multi page folder view path
     * @param $view - view path we are looking for
     * @return string - the custom page function name
     */
    private function getBulkView($view): string
    {
        $lastSlashPosition = strrpos($view, '/');
        if ($lastSlashPosition !== false) {
            $view = substr($view, 0, $lastSlashPosition);
        }
        return $view;
    }

    /**
     * This function will generate & return custom page folder view function name from the path
     * @param $view - view path
     * @return string
     */
    private function getBulkFunc($view): string
    {
        return str_replace('/', '_', (str_replace('-', '', $this->getBulkView($view))));
    }

    /**
     * Expand to folder vision
     * @param $view - the path we are looking for
     * @return void
     */
    private function expand($view): void
    {
        # Expand page
        $this->page += 1;

        if (method_exists($this, "__".$this->getBulkFunc($this->method."/".$view)."__") && file_exists($_SESSION['VIEWFOLDER'].$this->getBulkView($this->method."/".$view)."/index.view.php"))
        {
            call_user_func(array($this, "__".$this->getBulkFunc($this->method."/".$view)."__"), $this->getBulkView($this->method."/".$view)."/index");
            exit(1);
        }
        # Check whether it is the end point or not
        elseif (count($this->params) == $this->page)
        {
            if (method_exists($this, $this->getFunc($view)) && file_exists($_SESSION['VIEWFOLDER'].$this->method."/".$view.".view.php"))
            {
                call_user_func(array($this, $this->getFunc($view)), $this->method."/".$view);
                exit(1);
            }
            else
            {
                $this->__404__();
            }
        }
        else
        {
            $view .= '/' . $this->params[$this->page];
            $this->expand($view);
        }

    }

    /**
     * Configure basic requirements for rendering view file
     * @return void
     */
    private function config(): void
    {
        // CONFIGURE PAGE
        require_once __DIR__."/../config/config.php";

        if (!defined('SETTINGS')) {
            // DEFINE WEB SETTINGS
            include_once ROOTFOLDER . "config/settings.php";
            $settings = new Settings();
            define('SETTINGS', $settings);
        }

        if (!defined('CONTROLLER')) {
            // DEFINE CONTROLLER
            require_once APPFOLDER . "AdminController.php";
            $controller = new AdminController();
            define('CONTROLLER', $controller);
        }
    }

    /**
     * This function will handle the routing or expanding the view
     * @return void
     */
    function view(): void
    {
        // call the default if no params
        if (empty($this->params))
        {
            $this->__DEFAULT__();
            exit(1);
        }

        // extract the custom function
        $view = $this->params[0];

        // expand the view
        $this->expand($view);
    }

    /**
     * This function is to call 404 if failed to prompt the requested page
     * @return void
     */
    function __404__(): void
    {
        // Send the user to 404.
        require_once $_SESSION['ROOTFOLDER'] . "paths/__404__.php";
        $page = new __404__($this->method, $this->params);
        $page->view();
    }
}
