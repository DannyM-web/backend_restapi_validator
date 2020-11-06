<?php

namespace Acme\TestCase;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    protected $app;


    public function setUp(): void
    {
        $this->app = require dirname(__DIR__) . '/bootstrap.php';
    }

    public function tearDown(): void
    {
    }
}
