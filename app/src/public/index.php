<?php

use Acme\Controller\DataController;
use Acme\Middleware\RoutingMiddleware\MissingDataKeyMiddleware;
use Acme\Middleware\RoutingMiddleware\MissingFieldMiddleware;
use Acme\Exception\Validation\ValidatorException;

require_once '/var/www/app/vendor/autoload.php';
require_once '../../config.php';

$app = new \Slim\App($config);

$c = $app->getContainer();
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {

        if ($error instanceof ValidatorException) {
            return $response->withStatus(400)->withJson([
                'error' => $error->getMessage()
            ]);
        }

        if ($error instanceof PDOException) {
            return $response->withStatus(400)->withJson([
                'error' => $error->getMessage()
            ]);
        }

        return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong!');
    };
};


$app->group('/v1/lead', function () use ($app) {
    $app->post('/evaluate', DataController::class . ':action');
    $app->post('/request', DataController::class . ':action');
})->add(new MissingDataKeyMiddleware())
    ->add(new MissingFieldMiddleware());


$app->run();
