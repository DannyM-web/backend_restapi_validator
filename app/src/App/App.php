<?php

namespace Acme\App;

require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

class App
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function get()
    {
        return $this->app;
    }
}

new App($app);
