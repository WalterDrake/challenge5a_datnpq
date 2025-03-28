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

        if (count($_POST) > 0) {
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
                $location = json_decode($myrow->location, true);

                // Ensure it's an array
                if (!is_array($location)) {
                    $location = []; // Initialize as an empty array if it's not already an array
                }

                // Add new file(s) from $_POST['location'] (assuming it's an array of filenames)
                if (!empty($_POST['location'])) {
                    
                    // Convert to array if it's not already an array
                    $newFiles = is_array($_POST['location']) ? $_POST['location'] : [$_POST['location']];

                    // Merge and remove duplicates
                    $location = array_unique(array_merge($location, $newFiles));
                }

                // Encode back to JSON
                $_POST['location'] = json_encode(array_values($location)); // Re-indexing to maintain numeric keys

                // Update the database
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
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $errors = array();
        $assignment = new Assignment();

        $_POST['submit'] = [];
        if (count($_FILES) > 0 && Auth::access('Student')) {
            // upload file
            if ($myfile = upload_file($_FILES, $id)) {
                foreach ($myfile as $file) {
                    $_POST['submit'][] = basename($file);
                }
            }

            $myrow = $assignment->first('assignment_id', $id);
            if (is_object($myrow)) {

                $submit = json_decode($myrow->submit, true);

                // Ensure it's an array
                if (!is_array($submit)) {
                    $submit = []; // Initialize as an empty array if it's not already an array
                }

                // Add new file(s) from $_POST['submit'] (assuming it's an array of filenames)
                if (!empty($_POST['submit'])) {

                    // Convert to array if it's not already an array
                    $newFiles = is_array($_POST['submit']) ? $_POST['submit'] : [$_POST['submit']];

                    // Merge and remove duplicates
                    $submit = array_unique(array_merge($submit, $newFiles));
                }

                // Encode back to JSON
                $_POST['submit'] = json_encode(array_values($submit)); // Re-indexing to maintain numeric keys

                // Update the database
                $assignment->update($myrow->id, $_POST);
            }
            $redirect = 'assignments/submit/' . $id;
            $this->redirect($redirect);
        }

        $row = $assignment->first('assignment_id', $id);
        $data['row'] = $row;
        $data['errors'] = $errors;

        if (Auth::access('Administrator') || Auth::access('Student')) {
            $this->view('assignments.submit', $data);
        } else {
            $this->view('access-denied');
        }
    }

    function delete($id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $assignment = new Assignment();

        if (Auth::access('Administrator') || Auth::access('Teacher')) {
            $myrow = $assignment->first('assignment_id', $id);
            if (is_object($myrow)) {

                $folder = dirname(__DIR__, 2) . "/public/assets/uploads/" . $myrow->assignment_id . "/";
                // remove folder
                if (file_exists($folder)) {
                    deleteFolder($folder);
                }
                $assignment->delete($myrow->id);
            }
            $this->redirect('assignments');
        } else {
            $this->view('access-denied');
        }
    }

    function dSubmit($id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $assignment = new Assignment();

        if (count($_POST) > 0 && Auth::access('Student')) {
            $myrow = $assignment->first('assignment_id', $id);
            $filenameToRemove = $_POST['filename'];

            if (is_object($myrow)) {
                $submit = json_decode($myrow->submit, true);

                if (is_array($submit)) {
                    // Remove the specific filename from the array
                    $submit = array_filter($submit, function ($file) use ($filenameToRemove) {
                        return $file !== $filenameToRemove;
                    });

                    // Re-encode the updated array to JSON
                    $submit = json_encode(array_values($submit)); // Reindex array to avoid gaps

                    unlink(dirname(__DIR__, 2) . "/public/assets/uploads/" . $myrow->assignment_id . "/submits/" . Auth::getUser_id() . "/" . $filenameToRemove);

                    // Save the updated JSON back to the database
                    $assignment->update($myrow->id, ['submit' => $submit]);
                }

                $folder = dirname(__DIR__, 2) . "/public/assets/uploads/" . $myrow->assignment_id . "/submits/" . Auth::getUser_id() . "/";

                // remove folder
                if (isFolderEmpty($folder)) {
                    rmdir($folder);
                }
            }
            $this->redirect('assignments/submit/' . $id);
        } else {
            $this->view('access-denied');
        }
    }

    function dEdit($id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $assignment = new Assignment();

        if (count($_POST) > 0 && Auth::access('Teacher')) {
            $myrow = $assignment->first('assignment_id', $id);
            $filenameToRemove = $_POST['filename'];

            if (is_object($myrow)) {
                $location = json_decode($myrow->location, true);

                if (is_array($location)) {
                    // Remove the specific filename from the array
                    $location = array_filter($location, function ($file) use ($filenameToRemove) {
                        return $file !== $filenameToRemove;
                    });

                    // Re-encode the updated array to JSON
                    $location = json_encode(array_values($location)); // Reindex array to avoid gaps

                    unlink(dirname(__DIR__, 2) . "/public/assets/uploads/" . $myrow->assignment_id . "/assignments/" . $filenameToRemove);

                    // Save the updated JSON back to the database
                    $assignment->update($myrow->id, ['location' => $location]);
                }
            }
            $this->redirect('assignments/edit/' . $id);
        } else {
            $this->view('access-denied');
        }
    }
}
