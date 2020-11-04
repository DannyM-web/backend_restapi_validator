<?php

namespace Acme\Database\Migrations;

class LeadsTable extends Migration
{
    public function execute(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS leads (
            id                INT AUTO_INCREMENT PRIMARY KEY,
            contact_id        INT NOT NULL,
            contact_phone_id  INT NOT NULL,
            typology          VARCHAR(32),
            surface           INT,
            floor             INT,
            status            VARCHAR(32),
            address           VARCHAR(255),
            latitude          DECIMAL(12,8),
            longitude         DECIMAL(12,8),
            type              ENUM('evaluate','request') NOT NULL,
            property_id       INT NOT NULL,
            created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_contactId
            FOREIGN KEY (contact_id) 
            REFERENCES contacts(id)
                ON UPDATE CASCADE
                ON DELETE CASCADE,
            CONSTRAINT fk_contact_phone
            FOREIGN KEY (contact_phone_id) 
            REFERENCES contact_phone(id)
                ON UPDATE CASCADE
                ON DELETE CASCADE
                )";
        $this->getDb()->exec($sql);
        echo 'Table Leads created successfully' . PHP_EOL;
    }

    public function rollback(): void
    {
        $sql = "DROP TABLE IF EXISTS leads";
        $this->getDb()->exec($sql);
        echo 'Table Leads dropped' . PHP_EOL;
    }
}
