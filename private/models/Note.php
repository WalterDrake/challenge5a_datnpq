<?php

/**
 * Note model
 */
class Note extends Model
{
    public $errors = [];
    protected $beforeInsert = ['make_note_id'];
    protected $beforeUpdate = [];
    protected $allowedColumns = ['sender', 'receiver', 'note'];

    public function validate($DATA)
    {
        $this->errors = array();

        // check for note
        if (empty($DATA['note'])) {
            $this->errors['note'] = 'Please enter a note';
        }

        // check for receiver existance
        if (isset($DATA['receiver_id'])) {
            if (!$this->query('SELECT * FROM users WHERE user_id = :user_id', [':user_id' => $DATA['receiver_id']])) {
                $this->errors['receiver'] = 'Receiver does not exist';
            }
        }

        // check for sender existance
        if (isset($DATA['sender_id'])) {
            if (!$this->query('SELECT * FROM users WHERE user_id = :user_id', [':user_id' => $DATA['sender_id']])) {
                $this->errors['sender'] = 'Sender does not exist';
            }
        }

        // check for note_id existance
        if (isset($DATA['note_id'])) {
            if (!$this->query('SELECT * FROM notes WHERE note_id = :note_id', [':note_id' => $DATA['note_id']])) {
                $this->errors['note_id'] = 'Note does not exist';
            }
        }

        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }

    function make_note_id($data)
    {
        $data['note_id'] = uniqid();
        return $data;
    }
}
