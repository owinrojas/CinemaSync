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

    public $db;
    public $chiper;

    function __construct($DB_NAME, $DB_HOST, $DB_USER, $DB_PASSWORD)
    {
        $this->db = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
    }

    function getUser($email)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam("email", $email);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }

        foreach ($stmt->fetchAll() as $row) {
            $id = $row['id'];
            return new User($id, $this);
        }
    }

    function checkUser($email, $password)
    {
        $stmt = $this->db->prepare("SELECT id, password FROM users WHERE email = :email");
        $stmt->bindParam("email", $email);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return false;
        }
        foreach ($stmt->fetchAll() as $row) {
            $hash = $row['password'];
        }
        return password_verify($password, $hash);
    }

    function addUser($email, $password)
    {
        $date = date('Y-m-d H:i:s');
        //Check if username exist
        if ($this->getUser($email) !== null) {
            return false;
        }
        //Check if email exists
        $get = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $get->bindParam("email", $email);
        $get->execute();
        if ($get->rowCount() !== 0) {
            return false;
        }

        //Add user
        $length = 8;

        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        }

        $ref = substr(bin2hex($bytes), 0, $length);

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt1 = $this->db->prepare("INSERT INTO users (email, password, updated_at, created_at) VALUES (:email,:pass, :updated_at, :created_at)");
        $stmt1->bindParam("email", $email);
        $stmt1->bindParam("pass", $hashed);
        $stmt1->bindParam('updated_at', $date);
        $stmt1->bindParam("created_at", $date);
        $stmt1->execute();
        return true;
    }

    function getUserCount()
    {
        $stmt = $this->db->prepare("SELECT null FROM users");
        $stmt->execute();
        return $stmt->rowCount();
    }



    function returnUsers()
    {
        $htmlCode = "";
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            $user = new User($row['id'], $this);
            $rank = "User";


            $htmlCode .= '
      <tr>
       <th scope="row">' . $user->id . '</th>
       <td>' . $user->getData('email') . '</td>
       <td>' . $rank . '</td>
      </tr>';
        }

        return $htmlCode;
    }
}
