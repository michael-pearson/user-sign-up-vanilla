<?php 

require_once '../../controllers/helpers.php';
require_once absoluteInclude('/controllers/CSRFController');
require_once absoluteInclude('/controllers/AuthController');

// Create a new auth controller.
$authController = new AuthController();

// Check to see if the user is already logged in.
$authController->isAuthenticated();

// Check to see if the user is trying to register
$isRegistering = $authController->isRegistering();

// If we are registering.
if($isRegistering)
{
    // Attempt to register.
    $authController->register();
}

// Otherwise we will display the register screen with any errors.

// Include the header html.
include_once(absoluteInclude('/views/pages/includes/header')); 

?>

<h1>Register</h1>
<p>Use the below form to register, or click <a href="login.php">here</a> to login</p>

<form action="register.php" method="post">

    <input type="hidden" name="csrf" value="<?= CSRFController::generateToken() ?>"></input>
    
    <fieldset>
        <legend>Register</legend>

        <?php

            // If we have any errors.
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
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= getFlash('email') ?>"></input>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password"></input>
        </div>

        <div>
            <input type="submit" value="Register"></input>
        </div>
    </fieldset>

</form>

<?php

// Include the footer html.
include_once(absoluteInclude('/views/pages/includes/footer'));