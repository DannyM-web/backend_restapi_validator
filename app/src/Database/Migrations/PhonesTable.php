<?php

namespace Acme\Database\Migrations;

class PhonesTable extends Migration
{

    public function execute(): void
    {

        $sql = "CREATE TABLE IF NOT EXISTS phones (
                id     INT AUTO_INCREMENT PRIMARY KEY,
                phone VARCHAR(16) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )";

        $this->getDb()->exec($sql);
        echo "Table phones created successfully" . PHP_EOL;
    }

    public function rollback(): void
    {
        $sql = "DROP TABLE IF EXISTS phones ";
        $this->getDb()->exec($sql);
        echo 'RollBack phones Table' . PHP_EOL;
    }
}
