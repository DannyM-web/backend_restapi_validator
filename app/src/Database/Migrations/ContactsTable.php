<?php

namespace Acme\Database\Migrations;

class ContactsTable extends Migration
{
    
    public function execute():void
    {   
        
            $sql = "CREATE TABLE IF NOT EXISTS contacts (
                id     INT AUTO_INCREMENT PRIMARY KEY,
                name   VARCHAR(32) NOT NULL,
                surname VARCHAR(32) NOT NULL,
                email VARCHAR(128) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT uc_name_surname_email UNIQUE (name , surname, email)
                    )";
        
            $this->getDb()->exec($sql);
            echo "Table contacts created successfully" . PHP_EOL;
        
    }

    public function rollback():void
    {
        $sql = "DROP TABLE IF EXISTS contacts ";
        $this->getDb()->exec($sql);
        echo 'RollBack contacts Table'. PHP_EOL;
    }
}