<?php

namespace Tests\Feature\Feature;

use App\Events\NewOrderReceived;
use App\Models\Accessory;
use App\Models\MenuItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

        /*$this->visit(route('checkout'))
            ->*/
    }

    /**
     * @test
     */
    public function cardCheckoutStory()
    {
        $menu_item = factory(MenuItem::class)->make();
        $menu_item->save();
        $acc = factory(Accessory::class)->create();
        $menu_item->accessories()->attach($acc->id);
        // build cart
        $cart[0]['menu_item_model'] = $menu_item;
        $cart[0]['quantity'] = 1;
        $cart[0]['special_instructions'][] = 'Can ketchup please';
        $cart[0]['extras'][] = [$acc->id];

        $this->expectsEvents(NewOrderReceived::class);

        $this->withSession(['cart' => $cart])
            ->visit(route('checkout'))
            ->type('Daniel Evans','name')
            ->type('4242424242424242','card_number')
            ->type('08','expire_month')
            ->type('17','expire_year')
            ->type('123','cvc')
            ->type('evansdb0@sewanee.edu','email_address')
            ->press('Pay Now');
            //->seePageIs(route('thankYou'));
            $this->seeInDatabase('orders',['c_name' => 'Daniel Evans'])
            ->seeInDatabase('menu_items_orders',['menu_item_id' => $cart[0]['menu_item_model']->id]);
    }
}
