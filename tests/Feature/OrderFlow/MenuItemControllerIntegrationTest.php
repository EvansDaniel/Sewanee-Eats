<?php

namespace Tests\Feature\OrderFlow;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MenuItemControllerIntegrationTest extends TestCase
{
    use DatabaseMigrations;

    public function testTemp()
    {
        self::assertEquals(true, true);
    }
}
