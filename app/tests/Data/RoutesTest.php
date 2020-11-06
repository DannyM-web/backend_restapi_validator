<?php

namespace Acme\TestCase\Data;

use Acme\Database\Migrations\ContactPhoneTable;
use Acme\Database\Migrations\ContactsTable;
use Acme\Database\Migrations\LeadsTable;
use Acme\Database\Migrations\MigrationContract;
use Acme\Database\Migrations\PhonesTable;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Faker\Factory;

use Acme\TestCase\TestCase as MyTestCase;

class RoutesTest extends MyTestCase
{

    private $payload;
    private $install;
    private $migrations_to_drop;

    public function setUp(): void
    {
        parent::setUp();

        $this->install = require dirname(dirname(__DIR__)) . '/install.php';

        $faker = \Faker\Factory::create();

        $this->payload = [
            "contact" => [
                "name" => $faker->firstname(),
                "surname" => "Rossi",
                "email" => $faker->email(),
                "phone" => "3467555555",
                "property_id" => 4
            ],
            "evaluate" => [
                "typology" => "villa",
                "surface" => 45,
                "floor" => 5,
                "condition" => "buono stato",
                "address" => "Via mora 20",
                "latitude" => 45.999999,
                "longitude" => 45.999999
            ]
        ];
    }

    public function tearDown(): void
    {
        $this->migrations_to_drop = [
            new LeadsTable($this->app->getContainer()['db']),
            new ContactPhoneTable($this->app->getContainer()['db']),
            new ContactsTable($this->app->getContainer()['db']),
            new PhonesTable($this->app->getContainer()['db']),
        ];

        foreach ($this->migrations_to_drop as $migration) {
            if ($migration instanceof MigrationContract) {
                $migration->rollback();
            }
        }
    }

    public function testAbc()
    {
        $this->assertTrue(true);
    }

    public function testEvaluateRouteWithOkStatus()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/lead/evaluate',
            'CONTENT_TYPE'   => 'application/json',
        ]);
        $req = Request::createFromEnvironment($env)->withParsedBody($this->payload);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testEvaluateRouteWithErorStatus()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/lead/evaluate',
            'CONTENT_TYPE'   => 'application/json',
        ]);

        unset($this->payload['evaluate']['typology']);

        $req = Request::createFromEnvironment($env)->withParsedBody($this->payload);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 400);
    }

    public function testRequestRouteWithOkStatus()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/lead/request',
            'CONTENT_TYPE'   => 'application/json'
        ]);
        unset($this->payload['evaluate']);

        $req = Request::createFromEnvironment($env)->withParsedBody($this->payload);


        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        var_dump($response->getBody()->__toString());

        $this->assertSame($response->getStatusCode(), 200);
    }


    public function testRequestRouteWithErrorStatus()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/lead/request',
            'CONTENT_TYPE'   => 'application/json'
        ]);

        unset($this->payload['evaluate']);
        unset($this->payload['contact']['name']);

        $req = Request::createFromEnvironment($env)->withParsedBody($this->payload);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 400);
    }


    public function testRouteWithInvalidEmail()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/lead/evaluate',
            'CONTENT_TYPE'   => 'application/json'
        ]);

        $this->payload['contact']['email'] = 'test.it';
        $req = Request::createFromEnvironment($env)->withParsedBody($this->payload);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 400);
    }
}
