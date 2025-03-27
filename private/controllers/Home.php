<?php
/**
 * Home controller
 */
class Home extends Controller
{
    public function index()
    {
        $user = new User();
        if(!Auth::logged_in())
        {
            $this->redirect('login');
        }
        $data = $user->findAll();
        echo $this->view('home', ['rows' => $data]);
    }

    public function add()
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        if (count($_POST) > 0) {
            $user = new User();          
            if ($user->validate($_POST)) {  
                $user->insert($_POST);
            } else {
                $errors = $user->errors;
            }
        }
        $this->view('home.add', [
            'errors' => $errors
        ]);
    }
}
