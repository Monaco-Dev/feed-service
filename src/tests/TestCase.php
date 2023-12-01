<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Notification;
use Event;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $connectionsToTransact = ['auth_mysql', 'mysql'];

    protected $route;

    /**
     * Set up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Event::fake();
    }
}
