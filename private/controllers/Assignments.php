<?php
/**
 * Assignment controller
 */
class Assignments extends Controller
{
    // assignment page
    function index()
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $assignment = new Assignment();
        $data = $assignment->findAll();
        $this->view('assignments', [
            'row' => $data,
        ]);
    }

    function add()
    {
        $errors = array();
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        if (count($_POST) > 0) 
        {
            $assignment = new Assignment();          
            if ($assignment->validate($_POST)) { 
                $assignment->insert($_POST);
            } else {
                $errors = $assignment->errors;
            }
        }
        // return list of users for author dropdown
        $user = new User();
        $data = $user->findAll();

        $this->view('assignments.add', [
            'errors' => $errors,
            'data' => $data,
        ]);
    }

    function edit($id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $errors = array();
        $assignment = new Assignment();

        $_POST['location'] = [];
        if (count($_FILES) > 0) {
            // upload file
            if ($myfile = upload_file($_FILES, $id)) {
                foreach ($myfile as $file) {
                    $_POST['location'][] = basename($file);
                }
            }

            $myrow = $assignment->first('assignment_id', $id);
            if (is_object($myrow)) {
                $_POST['location'] = json_encode($_POST['location']);
                $assignment->update($myrow->id, $_POST);
            }
            $redirect = 'assignments/edit/' . $id;
            $this->redirect($redirect);

        }

        $row = $assignment->first('assignment_id', $id);
        $data['row'] = $row;
        $data['errors'] = $errors;

        if (Auth::access('Administrator') || Auth::access('Teacher')) {
            $this->view('assignments.edit', $data);
        } else {
            $this->view('access-denied');
        }
    }

    function submit($id = '')
    {

    }

    function delete($id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $assignment = new Assignment();
        if (count($_POST) > 0 && Auth::access('Administrator') || Auth::access('Teacher')) 
        {
            $myrow = $assignment->first('assignment_id', $id);
            if (is_object($myrow)) {
                $assignment->delete($myrow->id);
            }
            $this->redirect('assignments');
        } else {
            $this->view('access-denied');
        }
    }
}