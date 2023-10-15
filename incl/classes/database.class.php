<?php

/**
 *
 * Database Class
 *
 * @version 1.0
 *
 */

class Database
{

    public PDO $db;

    /**
     * @param $DB_NAME
     * @param $DB_HOST
     * @param $DB_USER
     * @param $DB_PASSWORD
     */
    function __construct($DB_NAME, $DB_HOST, $DB_USER, $DB_PASSWORD)
    {
        $this->db = new PDO('mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
    }

    /**
     * function returns a User object if email exists
     * @param $email
     * @return User|null
     */
    function getUser($email): User|null
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->bindParam('email', $email);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }
        foreach ($stmt->fetchAll() as $row) {
            $id = $row['id'];
            return new User($id, $this);
        }
        return null;
    }

    /**
     * Function to validate user login
     * @param $email
     * @param $password
     * @return bool
     */
    function checkUser($email, $password): bool
    {
        $hash = '';

        $stmt = $this->db->prepare('SELECT id, password FROM users WHERE email = :email');
        $stmt->bindParam('email', $email);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return false;
        }
        foreach ($stmt->fetchAll() as $row) {
            $hash = $row['password'];
        }
        return password_verify($password, $hash);
    }

    /**
     * Function to add user to database
     * @param $email
     * @param $password
     * @return bool
     * @throws Exception
     */
    function addUser($name, $email, $password): bool
    {
        $date = date('Y-m-d H:i:s');
        if ($this->getUser($email) !== null) {
            throw new Exception('User already exists');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid E-Mail');
        }
        if (strlen($password) < 8) {
            throw new Exception('Password must be 8 characters or longer');
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password, updated_at, created_at) VALUES (:name, :email, :password, :updated_at, :created_at)');
        $stmt->bindParam('name', $name);
        $stmt->bindParam('email', $email);
        $stmt->bindParam('password', $password_hash);
        $stmt->bindParam('updated_at', $date);
        $stmt->bindParam('created_at', $date);
        return $stmt->execute();

    }

    /**
     * returns # of users in the database (to be used later)
     * @return int
     */
    function getUserCount(): int
    {
        $stmt = $this->db->prepare('SELECT null FROM users');
        $stmt->execute();
        return $stmt->rowCount();
    }


    /**
     * returns all the users in an html table (to be used later)
     * @return string
     */
    function returnUsers(): string
    {
        $htmlCode = '';
        $stmt = $this->db->prepare('SELECT * FROM users');
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            $user = new User($row['id'], $this);
            $rank = 'User';


            $htmlCode .= '
      <tr>
       <th scope=\'row\'>' . $user->id . '</th>
       <td>' . $user->getData('email') . '</td>
       <td>' . $rank . '</td>
      </tr>';
        }

        return $htmlCode;
    }
}
