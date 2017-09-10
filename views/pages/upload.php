<?php

require_once '../../controllers/helpers.php';
require_once absoluteInclude('/controllers/FileController');

// Create a new file controller.
$fileController = new FileController();

// Upload the file.
$fileController->upload();

// Redirect back to the index.
redirect('index');