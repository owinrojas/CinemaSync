<?php

/**
 *
 * User Class for Generator Template
 *
 * @version 1.0
 *
 */
class User
{
    public $id;
    public $db;

    function __construct($id, $db)
    {
        $this->id = $id;
        $this->db = $db;
    }

    function isAdmin()
    {
        $rank = $this->getData("rank");
        if ($rank === '1') {
            return true;
        }
        return false;
    }
    function getData($data)
    {
        $stmt = $this->db->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam("id", $this->id);
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            return $row[$data];
        }
        return null;
    }
}
