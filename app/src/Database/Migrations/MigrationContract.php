<?php

namespace Acme\Database\Migrations;

interface MigrationContract
{
    public function execute():void;
    public function rollback():void;
}