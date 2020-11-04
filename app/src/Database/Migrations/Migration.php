<?php

namespace Acme\Database\Migrations;

use Acme\Database\DbConnection;
use Acme\Database\Migrations\MigrationContract;

class Migration implements MigrationContract
{
    private $db;

    public function __construct(DbConnection $db)
    {
        $this->db = $db;

    }

    protected function getDb()
    {
        return $this->db;
    }

    public function execute():void{

    }

    public function rollback():void{

    }
}