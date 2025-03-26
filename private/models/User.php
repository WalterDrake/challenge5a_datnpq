<?php

/**
 * User model
 */
class User extends Model
{
    public $errors = [];
    protected $beforeInsert = ['make_user_id', 'hass_password'];
    protected $beforeUpdate = ['hass_password'];
    protected $allowedColumns = ['email', 'numbers', 'password', 'role', 'fullname', 'username', 'avatar'];

    public function validate($DATA, $id = '')
    {
        $this->errors = array();

        // check for username
        if (trim($id) == "") {
            if (empty($DATA['username'])) {
                $this->errors['username'] = 'Please enter a username';
            }
        }

        //check for username existance
        if (trim($id) == "") {
            if ($this->where('username', $DATA['username'])) {
                $this->errors['username'] = 'Username already exists';
            }
        } elseif ($id && isset($DATA['username'])) {
            if ($this->query('SELECT * FROM users WHERE username = :username AND user_id != :id', [':username' => $DATA['username'], ':id' => $id])) {
                $this->errors['username'] = 'Username already exists';
            }
        }

        // check for full name
        if (trim($id) == "") {
            if (empty($DATA['fullname']) || !preg_match('/^[a-zA-Z\s]+$/', $DATA['fullname'])) {
                $this->errors['fullname'] = 'Please enter a valid full name';
            }
        } elseif ($id && isset($DATA['fullname'])) {
            if (!preg_match('/^[a-zA-Z\s]+$/', $DATA['fullname'])) {
                $this->errors['fullname'] = 'Please enter a valid full name';
            }
        }

        // check for email
        if (trim($id) == "") {
            if (empty($DATA['email']) || !filter_var($DATA['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Please enter a valid email address';
            }
        } elseif ($id && isset($DATA['email'])) {
            if (!filter_var($DATA['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Please enter a valid email address';
            }
        }

        // check for email existance
        if (trim($id) == "") {
            if ($this->where('email', $DATA['email'])) {
                $this->errors['email'] = 'Email already exists';
            }
        } elseif ($id && isset($DATA['email'])) {
            if ($this->query('SELECT * FROM users WHERE email = :email AND user_id != :id', [':email' => $DATA['email'], ':id' => $id])) {
                $this->errors['email'] = 'Email already exists';
            }
        }

        // check for phone number
        if (trim($id) == "") {
            if (empty($DATA['numbers']) || !preg_match('/^([0-9\s\-\+\(\)]*)$/', $DATA['numbers'])) {
                $this->errors['numbers'] = 'Please enter a valid phone number';
            }
        } elseif ($id && isset($DATA['numbers'])) {
            if (!preg_match('/^([0-9\s\-\+\(\)]*)$/', $DATA['numbers'])) {
                $this->errors['numbers'] = 'Please enter a valid phone number';
            }
        }

        // check for password
        if (isset($DATA['password'])) {
            if (empty($DATA['password'])) {
                $this->errors['password'] = 'Please enter a password';
            }
        }

        // check for role
        if (trim($id) == "") {
            if (empty($DATA['role'])) {
                $this->errors['role'] = 'Please select a role';
            } else if (!in_array($DATA['role'], ['Administrator', 'Student', 'Teacher'])) {
                $this->errors['role'] = 'Invalid role selected';
            }
        } elseif ($id && isset($DATA['role'])) {
            if (!in_array($DATA['role'], ['Administrator', 'Student', 'Teacher'])) {
                $this->errors['role'] = 'Invalid role selected';
            }
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
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}
