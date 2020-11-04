<?php

namespace Acme\Database\Models;

use PDOException;

class ContactPhone extends Model
{
    public function create($object)
    {
        $sql = "INSERT INTO contact_phone(
           contact_id,
           phone_id
        ) VALUES(
            :contact_id,
            :phone_id
        )";

        $stmt = $this->getDb()->prepare($sql);

        $stmt->bindParam(':contact_id', $object['contact_id']);
        $stmt->bindParam(':phone_id', $object['phone_id']);

        $stmt->execute();
    }

    public function getLastId()
    {
        return $this->getDb()->lastInsertId();
    }
}
