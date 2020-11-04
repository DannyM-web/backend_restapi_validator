<?php

namespace Acme\Database\Models;

use Acme\Database\DbConnection;

class Model
{
    private $db;


    public function __construct(DbConnection $db)
    {

        $this->db = $db;
    }


    public function getDb()
    {
        return $this->db;
    }
}
