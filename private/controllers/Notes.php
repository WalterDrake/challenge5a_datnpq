<?php

/**
 * Notes controller
 */
class Notes extends Controller
{
    function index()
    {
        $user = new User();
        $row = $user->first("user_id", Auth::getUser_id());

        $note = new Note();
        $notes = $note->where("receiver", $row->username);
        $this->view('notes', [
            'notes' => $notes,
        ]);
    }
}
