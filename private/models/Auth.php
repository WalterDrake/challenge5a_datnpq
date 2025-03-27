<?php
/**
 * Authentification class
 */
class Auth
{
    // Authenticate user
    public static function authenticate($row)
    {
        $_SESSION['USER'] = $row;
    }

    public static function logout()
    {
        if(isset($_SESSION['USER']))
        {
            unset($_SESSION['USER']);
        }
    }

    public static function logged_in()
    {
       if(isset($_SESSION['USER']))
       {
           return true;
       }
         return false;
    }

    public static function user()
    {
        if(isset($_SESSION['USER']))
        {
            return $_SESSION['USER']->username;
        }
        return false;
    }

    // Magic method to get user properties
    public static function __callStatic($method, $params)
    {
        $prop = strtolower(str_replace('get', '', $method));
        if(isset($_SESSION['USER']->$prop))
        {
            return $_SESSION['USER']->$prop;
        }
        return "Unknown property $prop";
    }

    // Check if user has access to a certain page
    public static function access($role = 'Student')
	{
		if(!isset($_SESSION['USER']))
		{
			return false;
		}

		$logged_in_role = $_SESSION['USER']->role;

        // Define roles
		$ROLE['Administrator'] 	= ['Administrator','Student','Teacher'];
        $ROLE['Teacher'] 		= ['Teacher','Student'];
		$ROLE['Student'] 		= ['Student'];

		if(!isset($ROLE[$logged_in_role]))
		{
			return false;
		}

		if(in_array($role,$ROLE[$logged_in_role]))
		{
			return true;
		}

		return false;
	}

    // Check if user owns content
    public static function i_own_content($row)
	{

		if(!isset($_SESSION['USER']))
		{
			return false;
		}

		if(isset($row->user_id)){

			if($_SESSION['USER']->user_id == $row->user_id){
				return true;
			}
		}

		$allowed[] = 'admin';

		if(in_array($_SESSION['USER']->role,$allowed)){
			return true;
		}

		return false;
	}
}