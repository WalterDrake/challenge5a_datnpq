<?php

/**
 * Profile controller
 */
class Profile extends Controller
{
    // profile page
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

    // edit profile
    function edit($id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $errors = array();
        $user = new User();
        $id = trim($id) == '' ? Auth::getUser_id() : $id;

        if (count($_FILES) > 0)
        {
             // upload image
             if($myimage = upload_image($_FILES, $id))
             {
                 $_POST['avatar'] = basename($myimage);
             }
        }

        if (count($_POST) > 0) {

            if ($user->validate($_POST, $id)) {

                $myrow = $user->first('user_id', $id);
                if (is_object($myrow)) {
                    $user->update($myrow->id, $_POST);
                }
                $redirect = 'profile/edit/' . $id;
                $this->redirect($redirect);

            } else {
                $errors = $user->errors;
            }
        }

        $row = $user->first('user_id', $id);
        $data['row'] = $row;
        $data['errors'] = $errors;

        if (Auth::access('Administrator') || Auth::access('Teacher') || Auth::i_own_content($row)) {
            $this->view('profile-edit', $data);
        } else {
            $this->view('access-denied');
        }
    }
}
