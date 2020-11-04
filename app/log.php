<?php

require __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('stderr');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::WARNING));

$logger->warning('hola');

