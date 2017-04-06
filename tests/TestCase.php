<?php

namespace Tests;

use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = 'http://localhost';

    /**
     * @param $class
     * @return Mockery\MockInterface
     */
    public function mock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }
}
