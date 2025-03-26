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
        if (file_exists("../private/views/" . $view . ".view.php")) {
            require("../private/views/" . $view . ".view.php");
        } else {
            require("../private/views/404.view.php");
        }
    }

    // Load model if it exists
    public function loadModel($model)
    {
        if (file_exists('../private/models/' . ucfirst($model) . '.php')) {
            require('../private/models/' . ucfirst($model) . '.php');
            return $model = new $model();
        }
        return false;
    }

    // Redirect
    public function redirect($link)
    {

        header("Location: " . ROOT . "/" . trim($link, "/"));
        die;
    }
}
