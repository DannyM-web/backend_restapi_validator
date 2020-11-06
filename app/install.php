<?php
require_once 'vendor/autoload.php';


use Acme\Database\DbConnection;
use Acme\Database\Migrations\ContactPhoneTable;
use Acme\Database\Migrations\ContactsTable;
use Acme\Database\Migrations\LeadsTable;
use Acme\Database\Migrations\PhonesTable;
use Acme\Database\Migrations\MigrationContract;

$config = require 'config.php';

$db = new DbConnection(
    $config['settings']['db']['host'],
    $config['settings']['db']['username'],
    $config['settings']['db']['password'],
    $config['settings']['db']['dbname']
);

$migrations = [
    new ContactsTable($db),
    new PhonesTable($db),
    new ContactPhoneTable($db),
    new LeadsTable($db),
];

try {

    foreach ($migrations as $migration) {
        if ($migration instanceof MigrationContract) {
            $migration->execute();
        }
    }
} catch (PDOException $th) {
    foreach ($migrations as $migration) {
        if ($migration instanceof MigrationContract) {
            $migration->rollback();
        }
    }
    echo 'Tabelle non create. Error' . $th->getMessage();
}
