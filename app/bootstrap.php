<?php

use Acme\Controller\DataController;
use Acme\Database\DbConnection;
use Acme\Database\Models\Contact;
use Acme\Database\Models\ContactPhone;
use Acme\Database\Models\Lead;
use Acme\Database\Models\Phone;
use Acme\Middleware\RoutingMiddleware\MissingDataKeyMiddleware;
use Acme\Middleware\RoutingMiddleware\MissingFieldMiddleware;
use Acme\Exception\Validation\ValidatorException;
use Acme\Services\StoreService;
use Slim\Container;

$config = require '/var/www/app/config.php';

if (!$config) {
    $config = [];
}

$container = new Container($config);

$app = new \Slim\App($config);

$c = $app->getContainer();
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {

        if ($error instanceof ValidatorException) {
            return $response->withStatus(400)->withJson([
                'error' => $error->getMessage()
            ]);
        } elseif ($error instanceof PDOException) {
            return $response->withStatus(400)->withJson([
                'error' => $error->getMessage()
            ]);
        } else {
            return $response->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write('Something went wrong!');
        }
    };
};

$c['db'] = function ($c) {
    try {
        return new DbConnection(
            $c['settings']['db']['host'],
            $c['settings']['db']['username'],
            $c['settings']['db']['password'],
            $c['settings']['db']['dbname']
        );
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
};

$c['service.store'] = function ($c) {

    $contact = new Contact($c['db']);
    $lead = new Lead($c['db']);
    $phone = new Phone($c['db']);
    $contactPhone = new ContactPhone($c['db']);

    return new StoreService($contact, $phone, $contactPhone, $lead, $c['db']);
};


$app->group('/v1/lead', function () use ($app) {
    $app->post('/evaluate', DataController::class . ':action');
    $app->post('/request', DataController::class . ':action');
})->add(new MissingDataKeyMiddleware())
    ->add(new MissingFieldMiddleware());

return $app;
