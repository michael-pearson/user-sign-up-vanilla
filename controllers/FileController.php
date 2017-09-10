<?php

require_once 'BaseController.php';
require_once 'AuthController.php';
require_once absoluteInclude('/database/Connection');

class FileController extends BaseController 
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
     * Uploads a file.
     */
    public function upload()
    {
        // If the CSRF token is not present.
        if(!isset($_POST['csrf']) || is_null($_POST['csrf']))
        {
            // Set the flash information.
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
            setFlash("errors", ["Form was submitted incorrectly, please try again."]);
            
            // Return false.
            return false;
        }

        // Set the max file size.
        $maxFileSize = 1048576;

        // Set the allowed extensions.
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'tiff', 'bmp'];

        // Set the allowed file types.
        $allowedFileTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/tiff', 'image/bmp'];

        // If there were not files to upload.
        if (!isset($_FILES['file']) || empty($_FILES['file']))
        {
            // Set the flash information.
            setFlash("errors", ["No file was selected, please try again."]);

            // Return false.
            return false;
        }

        // Check to make sure the extension is valid.
        if(!in_array(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION), $allowedExtensions))
        {
            // Set the flash information.
            setFlash("errors", ["Invalid file extension provided."]);

            // Return false.
            return false;
        }

        // Check to make sure the file is of the correct type.
        if(!in_array($_FILES['file']['type'], $allowedFileTypes))
        {
            // Set the flash information.
            setFlash("errors", ["Invalid file type."]);

            // Return false.
            return false;
        }

        // Check that the file is of the correct size.
        if($_FILES['file']['size'] > $maxFileSize)
        {
            // Set the flash information.
            setFlash("errors", ["File was too large!"]);

            // Return false.
            return false;
        }

        // Create the file upload path.
        $filePath = '../../uploads/' . $_SESSION['user'] . '/';

        // Check to see if the filename already exists.
        if(file_exists($filePath . $_FILES['file']['name']))
        {
            // Set the flash information.
            setFlash("errors", ["A file with this name already exists, change the name and try again."]);

            // Return false.
            return false;
        }

        // Make sure the user has a folder in uploads.
        if (!file_exists($filePath)) 
        {
            // Create the users folder.
            mkdir($filePath, 0777, true);
        }

        // Try to move the file.
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath . $_FILES['file']['name']))
        {
            // Set the flash information.
            setFlash("success", "File uploaded successfully!");

            // Return true.
            return true;
        }
        else
        {
            // Set the flash information.
            setFlash("errors", ["File not uploaded!"]);

            // Return false.
            return false;
        }
    }

    /**
     * Returns all uploaded files for the currently logged in user.
     */
    public function getAllUploadedForUser()
    {
        // Get the current user.
        $user = $_SESSION['user'];

        // If we don't have a user.
        if(is_null($user))
        {
            // Return false.
            return false;
        }

        // If the directory exists.
        if(!file_exists('../../uploads/' . $user))
        {
            // Return false.
            return false;
        }

        // Fetch all of the files for this user.
        $files = array_diff(scandir('../../uploads/' . $user), ['..', '.']);

        // Return files.
        return $files;
    }
}