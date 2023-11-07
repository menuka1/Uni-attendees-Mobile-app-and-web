<?php

class Paths 
{
    public function view($view, $data = array()): void
    {
        // Extract the data array into variables, so they can be used in the view file.
        extract($data);

        // Check if the specified view file exists.
        if (file_exists($_SESSION['ROOTFOLDER'] . "paths/" . $view . ".php") && ((file_exists($_SESSION['VIEWFOLDER'] . $view . ".view.php") || count($data)) && $view != "404"))
        {
            // Return the contents of the view file.
            require_once $_SESSION['ROOTFOLDER'] . "paths/" . $view . ".php";
            $page = new(str_replace("-", "", $view))($view, $data);
        }
        else 
        {
            // Send the user to 404.
            require_once $_SESSION['ROOTFOLDER'] . "paths/__404__.php";
            $page = new __404__($view, $data);
        }
        $page->view();
    }
}
