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

        $note = new Note();

        // return sender
        $sender = $user->first("user_id", Auth::getUser_id());
        // return receiver
        $receiver = $user->first("user_id", $id);

        $query = "SELECT note_id FROM notes WHERE sender = :sender AND receiver = :receiver";

        $note_id = $note->query($query, [':sender' => $sender->username, ':receiver' => $receiver->username]);
        if ($note_id) {
            $myrow = $note->first('note_id', $note_id[0]->note_id);
        } else {
            $myrow = [];
        }

        $data['row'] = $row;
        $data['notes'] = $myrow;

        $this->view('profile', $data);
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

        if (count($_FILES) > 0) {
            // upload image
            if ($myimage = upload_image($_FILES, $id)) {
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
            $this->view('profile.edit', $data);
        } else {
            $this->view('access-denied');
        }
    }

    // function edit
    function delete($id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $user = new User();
        $id = trim($id) == '' ? Auth::getUser_id() : $id;

        if (count($_POST) > 0 && Auth::access('Administrator') || Auth::access('Teacher')) {
            $myrow = $user->first('user_id', $id);
            if (is_object($myrow)) {
                $user->delete($myrow->id);
            }
            $this->redirect('home');
        } else {
            $this->view('access-denied');
        }
    }

    // function send note
    function send()
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        $errors = array();

        $note = new Note();
        $user = new User();

        if (count($_POST) > 0) {

            if ($note->validate($_POST)) {

                // if note_id is not set, it means we are creating a new note
                if (!isset($_POST['note_id'])) {

                    // return sender
                    $sender = $user->first("user_id", $_POST['sender_id']);
                    // return receiver
                    $receiver = $user->first("user_id", $_POST['receiver_id']);

                    $_POST['sender'] = $sender->username;
                    $_POST['receiver'] = $receiver->username;
                    unset($_POST['sender_id']);
                    unset($_POST['receiver_id']);
                    $_POST['note'] = json_encode([$_POST['note']]);
                    $note->insert($_POST);
                    echo json_encode(["success" => true]);
                    exit;
                } else {
                    // if note_id is set, it means we are updating an existing note
                    $myrow = $note->first('note_id', $_POST['note_id']);
                    if (is_object($myrow)) {
                        $notes = json_decode($myrow->note, true);

                        // Ensure it's an array
                        if (!is_array($notes)) {
                            $notes = []; // Initialize as an empty array if it's not already an array
                        }

                        // Add new file(s) from $_POST['note'] (assuming it's an array of filenames)
                        if (!empty($_POST['note'])) {

                            // Convert to array if it's not already an array
                            $newNotes = is_array($_POST['note']) ? $_POST['note'] : [$_POST['note']];

                            // Merge and remove duplicates
                            $notes = array_unique(array_merge($notes, $newNotes));
                        }

                        // Encode back to JSON
                        $_POST['note'] = json_encode(array_values($notes)); // Re-indexing to maintain numeric keys

                        // Update the database
                        $note->update($myrow->id, $_POST);
                        echo json_encode(["success" => true]);
                        exit;
                    }
                }
            } else {
                $errors = $note->errors;
            }
        }
        echo json_encode(["success" => false, "errors" => $errors]);
        exit;
    }

    function deleteNote($note_id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $note = new Note();
        $user = new User();

        if (count($_POST) > 0) {
            $myrow = $note->first('note_id', $note_id);
            if (is_object($myrow)) {

                $notes = json_decode($myrow->note, true);

                // Ensure it's an array
                if (!is_array($notes)) {
                    $notes = []; // Initialize as an empty array if it's not already an array
                }

                // Remove the note with the specified ID
                if (($key = array_search($_POST['note'], $notes)) !== false) {
                    unset($notes[$key]);
                }

                // Encode back to JSON
                $_POST['note'] = json_encode(array_values($notes)); // Re-indexing to maintain numeric keys

                // Update the database
                $note->update($myrow->id, $_POST);
                // Check if the note is empty after deletion
                if (empty($notes)) {
                    // If empty, delete the note entry from the database
                    $note->delete($myrow->id);
                }
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Note not found"]);
            }
        }
        exit;
    }

    // function edit note
    function editNote($note_id = '')
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }
        $note = new Note();
        $user = new User();

        if (count($_POST) > 0) {
            $myrow = $note->first('note_id', $note_id);
            if (is_object($myrow)) {
                $notes = json_decode($myrow->note, true);

                // Ensure it's an array
                if (!is_array($notes)) {
                    $notes = []; // Initialize as an empty array if it's not already an array
                }

                // Update the note with the specified ID
                if (($key = array_search($_POST['old_note'], $notes)) !== false) {
                    $notes[$key] = $_POST['new_note'];
                }

                // Encode back to JSON
                $_POST['note'] = json_encode(array_values($notes)); // Re-indexing to maintain numeric keys

                // Update the database
                $note->update($myrow->id, $_POST);
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Note not found"]);
            }
        }
        exit;
    }
}
