<?php

require_once 'BaseController.php';
require_once 'CSRFController.php';
require_once absoluteInclude('/models/User');
require_once absoluteInclude('/database/Connection');

class AuthController extends BaseController 
{
    /**
     * Constructor.
     */
	public function __construct()
    {
        // Call the parent constructor.
        parent::__construct();
    }

    /**
     * Check to see if the user is currently logged in.
     */
	public function authorise()
	{
        // If the user cookie is set.
        if (isset($_SESSION['user']) && !is_null($_SESSION['user'])) 
        {
            // Fetch the user.
            $user = User::getById($_SESSION['user']);

            // If we found the user.
            if ($user) 
            {
                // Return true.
				return $user;
            } 
            // If we didn't find the user.
            else 
            {
                // Return a new redirect to the login.
                return redirect('login');
                
                // Unset the session variable.
                unset($_SESSION['user']);
			}
        } 
        // If the user cookie isn't set.
        else 
        {
            // Return a new redirect to the login.
			return redirect('login');
		}
	}

    /**
     * Attempts to authenticate the user.
     */
	public function authenticate()
	{
        // Check if the username and password were present.
        if(!isset($_POST['username']) || !isset($_POST['password']))
        {
            // Set the flash data.
            setFlash("errors", ["Missing username or password."]);

            // Return false.
            return false;
        }

        // Retrieve the username and password and trim them.
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // If there is no username or password present.
        if(strlen($username) < 1 || strlen($password) < 1)
        {
            // Set the flash data.
            setFlash("errors", ["Missing username or password."]);

            // Return false.
            return false;
        }

        // If the CSRF token is not present.
        if(!isset($_POST['csrf']) || is_null($_POST['csrf']))
        {
            // Set the flash information.
            setFlash("username", $username);
            setFlash("errors", ["Form was submitted incorrectly, please try again."]);

            // Return false.
            return false;
        }

        // Check the CSRF token validity.
        $success = CSRFController::checkToken($_POST['csrf']);

        // If it wasn't valid.
        if(!$success)
        {
            // Set the flash data.
            setFlash("username", $username);
            setFlash("errors", ["Form was submitted incorrectly, please try again."]);

            // Return false.
            return false;
        }

        // Fetch the user.
        $user = User::getByUsernameAndPassword($username, $password);

        // If the user was found and the password was correct.
        if($user)
        {
            // Set the session user
            $_SESSION['user'] = $user->id;
    
            // Redirect to the index page.
            return redirect('index');
        }

        // Flash the error and username to the session
        setFlash("username", $username);
        setFlash("errors", ["Username or password incorrect."]);

        // Return false.
        return false;
    }
    
    /**
     * Attempts to register the user.
     */
	public function register()
	{
        // Check if the username and password were present.
        if(!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password']))
        {
            // Set the flash data.
            setFlash("errors", ["Missing username, email or password."]);

            // Return false.
            return false;
        }

        // Retrieve the username and password and trim them.
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // If there is no username or password present.
        if(strlen($username) < 1 || strlen($email) < 1 || strlen($password) < 1)
        {
            // Set the flash data.
            setFlash("errors", ["Missing username, email or password."]);

            // Return false.
            return false;
        }

        // If the CSRF token is not present.
        if(!isset($_POST['csrf']) || is_null($_POST['csrf']))
        {
            // Set the flash information.
            setFlash("username", $_POST['username']);
            setFlash("email", $_POST['email']);
            setFlash("errors", ["Form was submitted incorrectly, please try again."]);
            
            // Return false.
            return false;
        }

        // Check the CSRF token validity.
        $success = CSRFController::checkToken($_POST['csrf']);

        // If it wasn't valid.
        if(!$success)
        {
            // Set the flash information.
            setFlash("username", $_POST['username']);
            setFlash("email", $_POST['email']);
            setFlash("errors", ["Form was submitted incorrectly, please try again."]);
            
            // Return false.
            return false;
        }

        // If there is a user with this username, fail.
        if (User::getByUsername($username))
        {
            // Set the flash information.
            setFlash("username", $username);
            setFlash("email", $email);
            setFlash("errors", ["A user with this username already exists."]);

            // Return false.
            return false;
        }
        // Or if a user exists with this email, fail.
        else if (User::getByEmail($email))
        {
            // Set the flash information.
            setFlash("username", $username);
            setFlash("email", $email);
            setFlash("errors", ["A user with this email already exists."]);

            // Return false.
            return false;
        }

        // If the insert user fails
        if(!User::create($username, $email, $password))
        {
            // Set the flash information.
            setFlash("username", $username);
            setFlash("email", $email);
            setFlash("errors", ["Something went wrong, please try again."]);

            // Return false.
            return false;
        }

        // Set the flash data.
        setFlash("registered", true);

        // Otherwise, successfully registered, log them in.
        redirect('login');
    }
    
    /**
     * Logs out the currently logged in user.
     */
    public function logout()
    {
        // Log them out by removing the user session variable.
        unset($_SESSION['user']);

        // Send the user back to the index page.
        redirect('index');
    }
}