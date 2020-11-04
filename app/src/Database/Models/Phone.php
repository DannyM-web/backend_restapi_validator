<?php

namespace Acme\Database\Models;


class Phone extends Model
{
    public function create($object)
    {
        $sql = "INSERT INTO phones(
           phone
        ) VALUES(
            :phone
        )";

        $stmt = $this->getDb()->prepare($sql);

        $stmt->bindParam(':phone', $object['phone']);

        $stmt->execute();
    }

    public function getLastId()
    {
        return $this->getDb()->lastInsertId();
    }
}
