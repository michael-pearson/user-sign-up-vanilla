<?php
    // If we are on a secure server.
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) 
    {
        // Set the URI.
		$uri = 'https://';
    } 
    // If we are not on a secure server.
    else 
    {
        // Set the URI.
		$uri = 'http://';
    }
    
    // Concatenate the URI and the host name.
    $uri .= $_SERVER['HTTP_HOST'];
    
    // Redirect to the initial page.
	header('Location: '.$uri.'/views/pages/index.php');
    
    exit;
?>

<html>
    <body>
        <h3>Oops!</h3>
        <p>Looks like something went wrong!</p>
    </body>
</html>