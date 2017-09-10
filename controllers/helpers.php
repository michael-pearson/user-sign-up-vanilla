<?php

/**
 * Returns a url to a file absolute to the root of the project.
 *
 * @param string $_url
 * @return string
 */
function absoluteInclude(string $_url):string
{
    return $_SERVER['DOCUMENT_ROOT'] . $_url . '.php';
}

/**
 * Returns a string of specified length random characters.
 *
 * @param integer $_length
 * @return string
 */
function randomToken(int $_length = 10):string
{
    return bin2hex(random_bytes($_length));
}

/**
 * Aborts the current request.
 */
function abort()
{
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    exit();
}

/**
 * Redirects the user to the specified view.
 *
 * @param string $_url
 */
function redirect(string $_url)
{
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

    // Regenerate the session.
    session_regenerate_id(true);
    
    // Set the header to the new location.
    header('Location: ' . $uri . '/views/pages/' . $_url . '.php');
    
    // Close the session.
    session_write_close();
    
    exit();
}

/**
 * Returns true if we have any flash variables.
 *
 * @param string $_name
 * @return bool
 */
function hasFlash(string $_name):bool
{
    // If the session flash exists.
    if(isset($_SESSION['FLASH']) && !is_null($_SESSION['FLASH']))
    {
        // Return if the flash variable exists.
        return isset($_SESSION['FLASH'][$_name]) && !is_null($_SESSION['FLASH'][$_name]);
    }

    // Otherwise return false.
    return false;
}

/**
 * If the flash exists, return it's value. 
 *
 * @param string $_name
 */
function getFlash(string $_name)
{
    // If we have the flash.
    if(hasFlash($_name))
    {
        // Store the flash message.
        $flash = $_SESSION['FLASH'][$_name];

        // Unset the flash.
        unset($_SESSION['FLASH'][$_name]);

        // Return the flash by name.
        return $flash;
    }

    // Otherwise return null.
    return null;
}

/**
 * Sets a flash message.
 *
 * @param string $_name
 * @param any $val
 */
function setFlash(string $_name, $val)
{
    // Set the flash messae.
    $_SESSION['FLASH'][$_name] = $val;
}