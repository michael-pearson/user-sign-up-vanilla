<?php 

require_once '../../controllers/helpers.php';
require_once absoluteInclude('/controllers/CSRFController');
require_once absoluteInclude('/controllers/AuthController');

// Create a new auth controller.
$authController = new AuthController();

// Check to see if the user is already logged in.
$authController->isAuthenticated();

// Check to see if the user is trying to login
$isAuth = $authController->isAuthenticating();

// If we are authenticating.
if($isAuth)
{
    // Attempt to login.
    $authController->authenticate();
}

// Otherwise we will display the login with any errors.

// Include the header html.
include_once(absoluteInclude('/views/pages/includes/header')); 

?>

<h1>Login</h1>
<p>Use the below form to login, or click <a href="register.php">here</a> to register</p>

<?php

    if(hasFlash("registered"))
    {
        getFlash("registered");
        ?>
        <p>Thanks for registering, log in below.</p>
        <?php
    }

?>

<form action="login.php" method="post">

    <input type="hidden" name="csrf" value="<?= CSRFController::generateToken() ?>"></input>
    
    <fieldset>
        <legend>Login</legend>

        <?php
            // If we have any login errors.
            if(hasFlash('errors'))
            {
            ?>
            <p> We have found the following errors.</p>
            <ul>
                <?php

                // Loop through all of the errors and print them.
                foreach(getFlash('errors') as $error)
                {
                    echo "<li> " . $error . "</li>";
                }

                ?>
            </ul>
            <?php
            }
        ?>

        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?= getFlash('username') ?>"></input>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password"></input>
        </div>

        <div>
            <input type="submit" value="Login"></input>
        </div>
    </fieldset>

</form>

<?php

// Include the footer html.
include_once(absoluteInclude('/views/pages/includes/footer'));