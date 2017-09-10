<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/helpers.php';
require_once absoluteInclude('/database/Connection');

class User
{
    /**
     * The id of the user.
     *
     * @var int
     */
    public $id;

    /**
     * The username of the user.
     *
     * @var string
     */
    public $username;

    /**
     * The email of the user.
     *
     * @var string
     */
    public $email;

    /**
     * Constructor
     *
     * @param int $_id
     * @param string $_username
     * @param string $_email
     */
    public function __construct(int $_id, string $_username, string $_email)
    {
        $this->id = $_id;
        $this->username = $_username;
        $this->email = $_email;
    }

    /**
     * Returns a new user model by id.
     *
     * @param int $_id
     * @return User|null
     */
    public static function getById(int $_id):?User
    {
        // Fetch a PDO connection.
        $connection = Connection::getConnection();
    
        // Prepare the insert statement.
        $stmt = $connection->prepare
        ("
            SELECT      *
            FROM        users
            WHERE       id = :id
            AND         deleted_at IS NULL
        ");

        // Execute the statement
        $stmt->execute
        ([
            ":id" => $_id
        ]);

        // Fetch the first row.
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        // If we didn't find a user.
        if(!$row)
        {
            return null;
        }

        // Return a new user model.
        return new User($row->id, $row->username, $row->email);
    }

    /**
     * Returns a new user model by username.
     *
     * @param string $_username
     * @return User|null
     */
    public static function getByUsername(string $_username):?User
    {
        // Fetch a PDO connection.
        $connection = Connection::getConnection();
    
        // Prepare the insert statement.
        $stmt = $connection->prepare
        ("
            SELECT      *
            FROM        users
            WHERE       username = :username
            AND         deleted_at IS NULL
        ");

        // Execute the statement
        $stmt->execute
        ([
            ":username" => $_username
        ]);

        // Fetch the first row.
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        // If we didn't find a user.
        if(!$row)
        {
            return null;
        }

        // Return a new user model.
        return new User($row->id, $row->username, $row->email);
    }

    /**
     * Returns a new user model by email.
     *
     * @param string $_email
     * @return User|null
     */
    public static function getByEmail(string $_email):?User
    {
        // Fetch a PDO connection.
        $connection = Connection::getConnection();
    
        // Prepare the insert statement.
        $stmt = $connection->prepare
        ("
            SELECT      *
            FROM        users
            WHERE       email = :email
            AND         deleted_at IS NULL
        ");

        // Execute the statement
        $stmt->execute
        ([
            ":email" => $_email
        ]);

        // Fetch the first row.
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        // If we didn't find a user.
        if(!$row)
        {
            return null;
        }

        // Return a new user model.
        return new User($row->id, $row->username, $row->email);
    }

    /**
     * Fetches a username if the username and password match a record.
     *
     * @param string $_username
     * @param string $_password
     * @return User|null
     */
    public static function getByUsernameAndPassword(string $_username, string $_password):?User
    {
        // Get the connection.
        $connection = Connection::getConnection();
        
        // Prepare the user select statement.
        $stmt = $connection->prepare
        ("
            SELECT * FROM users
            WHERE   username=:username
        ");
        
        // Execute the statement
        $stmt->execute
        ([
            ":username" => $_username
        ]);

        // Fetch the first row.
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        // If we couldn't find the user.
        if(!$row)
        {
            // Return null.
            return null;
        }

        // If the password matches.
        if (password_verify($_password, $row->password))
        {
            // Return a new user.
            return new User($row->id, $row->username, $row->password);
        }

        // Otherwise return null.
        return null;
    }

    /**
     * Creates a new user record.
     *
     * @param string $_username
     * @param string $_email
     * @param string $_password
     * @return bool
     */
    public static function create(string $_username, string $_email, string $_password):bool
    {
        // Get the connection.
        $connection = Connection::getConnection();
        
        // Prepare the user insert statement.
        $stmt = $connection->prepare
        ("
            INSERT INTO users (username, email, password)
            VALUES (:username, :email, :password)
        ");
        
        // Execute the statement
        $success = $stmt->execute
        ([
            ":username" => $_username,
            ":email" => $_email,
            ":password" => password_hash($_password, PASSWORD_BCRYPT)
        ]);

        // Return the success.
        return $success;
    }
}
