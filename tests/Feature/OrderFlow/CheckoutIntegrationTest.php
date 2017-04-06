<?php

namespace Tests\Feature\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CheckoutIntegrationTest extends TestCase
{
    use DatabaseTransactions;


    public function checkoutNoItemsRedirectsHome()
    {
        $this->visit(route('checkout'))
            ->seePageIs(route('home'));
    }

    /**
     * @test
     */
    public function cardCheckoutStory()
    {

    }
}
