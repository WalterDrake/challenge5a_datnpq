<?php
class UserAdd extends Controller
{
    function index()
    {
        $errors = array();

        if (count($_POST) > 0) {
            $user = new User();          
            if ($user->validate($_POST)) {  
                $user->insert($_POST);
            } else {
                $errors = $user->errors;
            }
        }
        $this->view('user-add', [
            'errors' => $errors
        ]);
    }
}
