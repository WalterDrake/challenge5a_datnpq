<?php

/**
 * User model
 */
class User extends Model
{
    public $errors = [];
    protected $beforeInsert = ['make_user_id', 'hass_password'];
    protected $allowedColumns = ['email', 'numbers', 'password','role','fullname','username', 'avatar'];

    public function validate($DATA)
    {
        $this->errors = array();

        // check for username
        if (empty($DATA['username'])) {
            $this->errors['username'] = 'Please enter a username';
        }

        // check for full name
        if (empty($DATA['fullname']) || !preg_match('/^[a-zA-Z\s]+$/', $DATA['fullname'])) {
            $this->errors['fullname'] = 'Please enter a full name';
        }

        // check for email
        if (empty($DATA['email']) || !filter_var($DATA['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Please enter a valid email address';
        }

        // check for phone number
        if (empty($DATA['numbers']) || !preg_match('/^([0-9\s\-\+\(\)]*)$/', $DATA['numbers'])) {
            $this->errors['numbers'] = 'Please enter a valid phone number';
        }

        // check for password
        if (empty($DATA['password'])) {
            $this->errors['password'] = 'Please enter a password';
        }

        // check for role
        if (empty($DATA['role'])) {
            $this->errors['role'] = 'Please select a role';
        }
        else if (!in_array($DATA['role'], ['Administrator', 'Student', 'Teacher'])) {
            $this->errors['role'] = 'Invalid role selected';
        }

        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }

    public function make_user_id($data)
    {
        $data['user_id'] = uniqid();
        return $data;
    }

    public function hass_password($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $data;
    }
}
