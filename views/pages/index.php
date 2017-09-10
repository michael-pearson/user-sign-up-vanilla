<?php 

require_once '../../controllers/helpers.php';
require_once absoluteInclude('/controllers/AuthController');
require_once absoluteInclude('/controllers/CSRFController');
require_once absoluteInclude('/controllers/FileController');

// Include the header.
include_once(absoluteInclude('/views/pages/includes/header')); 

// Create a new auth controller.
$authController = new AuthController();

// Check to see if the user is authorised.
$user = $authController->authorise();

// If the user is logged in.
if (!is_null($user))
{
    // Create a new file controller.
    $fileController = new FileController();

    // Fetch any uploaded files for the current user.
    $files = $fileController->getAllUploadedForUser();
?>

    <h1>Welcome to the tech test, <?= $user->username ?></h1>
    <p>You can either <a href="logout.php">log out</a>, or upload a document using the below form.</p>

    <form action="upload.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="csrf" value="<?= CSRFController::generateToken() ?>"></input>

        <label for="file">Select file to upload.</label>
        <input type="file" name="file" id="file"></input>
        <input type="submit" value="Upload File" name="submit"></input>
    </form>

    <?php
    // If we succeeded in uploading a file.
    if(hasFlash('success'))
    {
        ?>

            <div><p><?=getFlash('success')?></p></div>

        <?php
    }

    // If we had any errors while uploading a file.
    if(hasFlash('errors'))
    {
    ?>
        <p> We have found the following errors.</p>
        <ul>
            <?php

            // Loop through and print all errors.
            foreach(getFlash('errors') as $error)
            {
                echo "<li> " . $error . "</li>";
            }

            ?>
        </ul>
    <?php
    }

    // If we have any uploaded files for the current user.
    if($files && count($files) > 0)
    {
        ?>
            <h3>Uploaded files.</h3>
            <ul>
            <?php
            
            // Loop through all of the files for this user and print their name.
            foreach($files as $file)
            {
                ?>
                  <li><?= $file ?></li>  
                <?php
            }
            ?>  
            </ul>
        <?php
    }
    // If we don't have any uploaded files for the current user.
    else
    {
        ?>
        <h3>Uploaded files.</h3>
        <p>No files found.</p>
        <?php
    }
}
// If the user isn't current logged in.
else
{
?>

<h1>Welcome to the tech test!</h1>
<p>To begin, please <a href="login.php">login</a>, or <a href="register.php">register</a>.</p>

<?php
}

// Include the footer.
include_once(absoluteInclude('/views/pages/includes/footer'));