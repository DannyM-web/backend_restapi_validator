<?php

namespace Acme\Database;

use PDO;

class DbConnection extends PDO
{
    public function __construct($host, $username, $password, $dbname)
    {
       parent:: __construct( "mysql:host=$host;dbname=$dbname",$username,$password);
       $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

}