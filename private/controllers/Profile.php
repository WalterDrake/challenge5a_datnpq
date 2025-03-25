<?php

/**
 * Profile controller
 */
class Profile extends Controller
{
    function index($id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $user = new User();
    
        $id = trim($id) == '' ? Auth::getUser_id() : $id;
        $row = $user->first('user_id', $id);
        $this->view('profile', [
            'row' => $row,
        ]);
    }
    function edit($id = '')
    {
        // if(!Auth::logged_in()) {
        //     $this->redirect('login');
        // }
        // $errors = array();
        // $user = new User();
        // $id = trim($id='') ? Auth::getUser_id() : $id;
        // $row = $user->first('user_id', $id);
        // if (count($_POST) > 0) {
        //     if ($user->validate($_POST)) {
        //         $user->update($id, $_POST);
        //     } else {
        //         $errors = $user->errors;
        //     }
        // }
        // $this->view('profile-edit', [
        //     'errors' => $errors
        // ]);
    }
}
