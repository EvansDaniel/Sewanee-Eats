<?php

namespace Tests\Feature\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Checks that a user can checkout properly via venmo
     * @test
     * @return void
     */
    public function venmoCheckoutStory()
    {

    }

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
