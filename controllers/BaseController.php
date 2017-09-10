<?php

require_once 'helpers.php';
require_once absoluteInclude('/controllers/AuthController');

class BaseController 
{
    /**
     * Constructor.
     */
	public function __construct()
    {
        // If the session hasn't already been started.
        if(!isset($_SESSION))
        {
            // Start the session.
            session_start();
        }
    }

    /**
     * If the current request is a login attempt, returns true,
     * otherwise returns false.
     *
     * @return boolean
     */
	public function isAuthenticating():bool
	{
        // If the username and password post parameters are set then we are attempting to login.
        if (isset($_POST['username']) && isset($_POST['password']))
        {
            // Return true.
			return true;
        }
        
        // Not attempting to login, return false.
        return false;
    }
    
    /**
     * If the current request is a register attempt, returns true,
     * otherwise returns false.
     *
     * @return boolean
     */
	public function isRegistering():bool
	{
        // If the username and password post parameters are set then we are attempting to register.
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']))
        {
            // Return true.
			return true;
        }
        
        // Not attempting to register, return false.
        return false;
    }

    /**
     * Checks to see if there is a currently logged in user.
     */
    public function isAuthenticated()
    {
        // If we are logged in.
        if (isset($_SESSION['user']) && !is_null($_SESSION['user']))
        {
            // Redirect to the index page.
            redirect('index');
        }

        // Otherwise return false.
        return false;
    }

}
