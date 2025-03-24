<?php

/**
 * Login controller
 */
class Login extends Controller
{
    public function index()
    {   
        if (count($_POST) > 0) {
            $user = new User();
            if ($row = $user->where('username', $_POST['username'])) {
                $row = $row[0];
                if (password_verify($_POST['password'], $row->password)) {
                    Auth::authenticate($row);
                    $this->redirect('home');
                }
            }
            $errors = ['Invalid username or password'];
        }
        $errors = array();
        $this->view('login', [
            'errors' => $errors
        ]);
    }
}
