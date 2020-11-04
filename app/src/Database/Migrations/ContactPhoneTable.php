<?php

namespace Acme\Database\Migrations;

class ContactPhoneTable extends Migration
{
    public function execute(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS contact_phone (
            id                INT AUTO_INCREMENT PRIMARY KEY,
            contact_id        INT NOT NULL,
            phone_id          INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
            CONSTRAINT fk_contact
            FOREIGN KEY (contact_id) 
            REFERENCES contacts(id)
                ON UPDATE CASCADE
                ON DELETE CASCADE,
            CONSTRAINT fk_phone
            FOREIGN KEY (phone_id) 
            REFERENCES phones(id)
                ON UPDATE CASCADE
                ON DELETE CASCADE
                )";
        $this->getDb()->exec($sql);
        echo 'Bridge Table contact_phone created successfully' . PHP_EOL;
    }

    public function rollback(): void
    {
        $sql = "DROP TABLE IF EXISTS contact_phone";
        $this->getDb()->exec($sql);

        echo 'table contact_phone dropped' . PHP_EOL;
    }
}
