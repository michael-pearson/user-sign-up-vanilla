<?php 

require_once '../../controllers/helpers.php';
require_once absoluteInclude('/controllers/AuthController');

// Create a new auth controller.
$authController = new AuthController();

// Try and log out.
$authController->logout();