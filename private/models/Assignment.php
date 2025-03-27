<?php

/**
 * Assigment model
 */
class Assignment extends Model
{
    public $errors = [];
    protected $beforeInsert = ['make_assignment_id'];
    protected $beforeUpdate = [];
    protected $allowedColumns = ['title', 'location', 'author', 'description', 'date', 'submit'];


    public function validate($DATA)
    {
        $this->errors = array();

        // check for title
        if (empty($DATA['title'])) {
            $this->errors['title'] = 'Please enter a title';
        }

        //check for title existance
        if ($this->where('title', $DATA['title'])) {
            $this->errors['title'] = 'Title already exists';
        }

        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }

    public function make_assignment_id($data)
    {
        $data['assignment_id'] = uniqid();
        return $data;
    }

}
