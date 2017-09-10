<?php

require_once 'helpers.php';
require_once 'BaseController.php';

abstract class CSRFController 
{
    /**
     * Generates a CSRF token and inserts it into the database.
     *
     * @return string
     */
    public static function generateToken():string
    {
        // Create a new token
        $token = randomToken(30);

        // Fetch a PDO connection.
        $connection = Connection::getConnection();
        
        // Prepare the insert statement.
        $stmt = $connection->prepare
        ("
            INSERT INTO csrf_tokens (token)
            VALUES  (:token)
        ");

        // Execute the statement
        $success = $stmt->execute
        ([
            ":token" => $token
        ]);

        // If we couldn't generate a token.
        if($success === false)
        {
            redirect('error');
        }

        // Return the token.
        return $token;
    }

    /**
     * Check to see if a token exists for the passed token string.
     *
     * @param string $_token
     * @return bool
     */
    public static function checkToken(string $_token):bool
    {
        // Fetch a PDO connection.
        $connection = Connection::getConnection();

        // Prepare the insert statement.
        $stmt = $connection->prepare
        ("
            SELECT      *
            FROM        csrf_tokens
            WHERE       token = :token
            AND         deleted_at IS NULL
        ");

        // Execute the statement
        $stmt->execute
        ([
            ":token" => $_token
        ]);

        // Fetch the first row.
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        // If we don't have a row.
        if(is_null($row) || !$row)
        {
            // Return false.
            return false;
        }

        // Otherwise, turn off the token so that it cannot be resubmitted.
        $stmt = $connection->prepare
        ("
            UPDATE      csrf_tokens
            SET         deleted_at = NOW()
            WHERE       id = :id
            AND         deleted_at IS NULL
        ");

        // Update the token.
        $success = $stmt->execute
        ([
            ":id" => $row->id
        ]);

        // If the token was already deleted or used.
        if($success === false)
        {
            // Return false.
            return false;
        }

        // Return true.
        return true;
    }
}