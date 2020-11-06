<?php

namespace Acme\Database\Models;


use Acme\Database\Models\Model;

class Lead extends Model
{

    public function create($object)
    {
        $sql = "INSERT INTO leads(
            contact_id,
            contact_phone_id,
            typology,
            surface,
            floor,
            status,
            address,
            latitude,
            longitude,
            type,
            property_id
        ) VALUES(
            :contact_id,
            :contact_phone_id,
            :typology,
            :surface,
            :floor,
            :status,
            :address,
            :latitude,
            :longitude,
            :type,
            :property_id
        )";

        $stmt = $this->getDb()->prepare($sql);

        $stmt->bindParam(':typology', $object['typology']);
        $stmt->bindParam(':surface', $object['surface']);
        $stmt->bindParam(':floor', $object['floor']);
        $stmt->bindParam(':status', $object['status']);
        $stmt->bindParam(':address', $object['address']);
        $stmt->bindParam(':latitude', $object['latitude']);
        $stmt->bindParam(':longitude', $object['longitude']);
        $stmt->bindParam(':property_id', $object['property_id']);
        $stmt->bindParam(':type', $object['type']);
        $stmt->bindParam(':contact_id', $object['contact_id']);
        $stmt->bindParam(':contact_phone_id', $object['contact_phone_id']);

        $stmt->execute();
    }

    public function getLastId()
    {
        return $this->getDb()->lastInsertId();
    }
}
