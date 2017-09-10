<?php

abstract class Connection
{
    /**
     * Creates a new PDO connection.
     *
     * @return PDO
     */
    public static function getConnection()
    {
        return new PDO(
            "mysql:host=localhost;dbname=hr",
            "system_user",
            "password"
        );
    }
}