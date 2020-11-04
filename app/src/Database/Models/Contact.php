<?php

namespace Acme\Database\Models;

use PDOException;

class Contact extends Model

{

    public function create($object)
    {
        $sql = "INSERT INTO `contacts`(
            name,
            surname,
            email
        ) VALUES(
            :name,
            :surname,
            :email
        )";

        $stmt = $this->getDb()->prepare($sql);

        $stmt->bindParam(':name', $object['name']);
        $stmt->bindParam(':surname', $object['surname']);
        $stmt->bindParam(':email', $object['email']);

        $stmt->execute();
    }

    public function getLastId()
    {
        return $this->getDb()->lastInsertId();
    }
}
