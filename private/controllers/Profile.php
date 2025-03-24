<?php
/**
 * Profile controller
 */
class Profile extends Controller
{
    function index()
    {
        if(!Auth::logged_in()) {
            $this->redirect('login');
        }
        $errors = array();
        $user = new User();
        $id = trim($id='') ? Auth::getUser_id() : $id;
        if(count($_POST) > 0) {
            if($user->validate($_POST)) {
                // save to database
                // $user->update($_POST, $_SESSION['user_id']);
            } else {
                $errors = $user->errors;
            }
        }
        $this->view('profile', [
            'errors' => $errors
        ]);
    }
}
