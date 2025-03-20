<?php
/**
 * main controller class
*/
class Controller
{
    // Load view
    public function view($view, $data = array())
    {
        extract($data);
        // Check if the view file exists
        if(file_exists("../private/views/" . $view . ".view.php"))
        {
            require ("../private/views/" . $view . ".view.php");
        }else
        {
            require ("../private/views/404.view.php");
        }
    }
}