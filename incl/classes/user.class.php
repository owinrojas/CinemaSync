<?php

/**
 *
 * User Class
 *
 * @version 1.0
 *
 */
class User
{
    public int $id; // user id
    public object $db; // database object
    private mixed $data; // user data

    /**
     * @param $id
     * @param $db
     */
    public function __construct($id, $db)
    {
        $this->id = $id;
        $this->db = $db; // use current database object
        $this->setData(); // get the user data and save to $data
    }

    /**
     * returns true if user is admin
     * will prob not be used
     * @return bool
     */
    public function isAdmin(): bool
    {
        $rank = $this->getData('rank');
        if ($rank === '1') {
            return true;
        }
        return false;
    }

    /**
     * return user data from the array
     * @param $data
     * @return mixed
     */
    public function getData($data): mixed
    {
        return $this->data[$data];
    }

    /**
     * function saves user data locally to limit number of queries made
     * @return void
     */
    public function setData(): void
    {
        $stmt = $this->db->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
        $this->data = $stmt->fetch();
    }

}
