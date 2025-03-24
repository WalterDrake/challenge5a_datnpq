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
}
