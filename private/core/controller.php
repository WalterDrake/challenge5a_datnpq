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

    // Upload file
    public function uploadImage($username)
    {
        if (!isset($_FILES["avatar"]) || $_FILES["avatar"]["error"] !== UPLOAD_ERR_OK) {
            return "Error: No file uploaded or an error occurred.";
        }

        $target_dir = dirname(__DIR__, 2) . "/public/uploads/";
        $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["avatar"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                return "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            return "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["avatar"]["size"] > 500000) {
            return "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if (
            !in_array($imageFileType, array('jpg', 'jpeg', 'png', 'gif'))
        ) {
            return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        echo $target_file;
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                return $target_file;
            } else {
                return  "Sorry, there was an error uploading your file.";
            }
        }
    }

    public function redirect($link)
    {

        header("Location: " . ROOT . "/" . trim($link, "/"));
        die;
    }
}
