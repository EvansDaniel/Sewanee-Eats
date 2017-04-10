<?php

namespace Tests\Unit\Order;

use App\CustomClasses\Courier\CourierTypes;
use App\CustomClasses\Orders\CustomerOrder;
use App\CustomClasses\ShoppingCart\CartBilling;
use App\CustomClasses\ShoppingCart\OnDemandBilling;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\CustomClasses\ShoppingCart\SpecialBilling;
use App\Models\Order;
use App\TestTraits\HandlesCartItems;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use DatabaseMigrations, HandlesCartItems;

    /**
     * @return void
     */
    public function testConstructor()
    {
        $cart = new ShoppingCart();
        $billing = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart));
        $customer_order = new CustomerOrder($cart, $billing, []);
        $this->assertAttributeNotEmpty('cart', $customer_order);
        $this->assertAttributeNotEmpty('billing', $customer_order);
        $this->assertAttributeEmpty('input', $customer_order);
    }

    public function itAssignsLocationForAddresses()
    {
        $cart = $this->mock(ShoppingCart::class);
        $cart->shouldReceive(['hasOnDemandItems' => true]);
        $order = factory(Order::class)->make([
            'courier_types' => json_encode([CourierTypes::DRIVER]),
            'payment_type' => PaymentType::STRIPE_PAYMENT,
            'order_types' => json_encode(['on_demand' => RestaurantOrderCategory::ON_DEMAND])
        ]);
        // empty input for building name, so addresss should be used
        $input = ['building_name' => '', 'area_type' => '', 'room_number' => '', 'address' => 'address'];
        $billing = $this->mock(CartBilling::class);
        $c_order = new CustomerOrder($cart, $billing, $input);
        $order = $c_order->handleDeliveryLocation($order);
        self::assertEquals($input['address'], $order->delivery_location);
    }

    /**
     * @test
     */
    public function itAssignsLocationForUniversityBuildings()
    {
        $cart = $this->mock(ShoppingCart::class);
        $cart->shouldReceive(['hasOnDemandItems' => true]);
        $order = factory(Order::class)->make([
            'courier_types' => json_encode([CourierTypes::DRIVER]),
            'payment_type' => PaymentType::STRIPE_PAYMENT,
            'order_types' => json_encode(['on_demand' => RestaurantOrderCategory::ON_DEMAND])
        ]);
        $input = [
            'building_name' => 'building_name',
            'area_type' => 'area_type',
            'room_number' => 'room_number'
        ];
        $billing = $this->mock(CartBilling::class);
        $c_order = new CustomerOrder($cart, $billing, $input);
        $order = $c_order->handleDeliveryLocation($order);
        self::assertContains($input['building_name'], $order->delivery_location);
        self::assertContains($input['area_type'], $order->delivery_location);
        self::assertContains($input['room_number'], $order->delivery_location);
    }

    /**
     * @test
     */
    public function saveOrderPriceInfoForStripeOnDemand()
    {
        $b = $this->mock(CartBilling::class);
        $b->shouldReceive([
            'getTotal' => 1, 'getSubtotal' => 2, 'getStripeFees' => 3, 'getProfit' => 4,
            'getCostOfFood' => 5, 'getDeliveryFee' => 6, 'getTax' => 7, 'getTaxPercent' => 8
        ]);
        // all we need is an order, the billing/cart stuff can be mocked
        $order = factory(Order::class)->create([
            'courier_types' => json_encode([CourierTypes::DRIVER]),
            'payment_type' => PaymentType::STRIPE_PAYMENT,
            'order_types' => json_encode(['on_demand' => RestaurantOrderCategory::ON_DEMAND])
        ]);
        $c_order = new CustomerOrder(new ShoppingCart(), $b, []);
        $c_order->saveOrderPriceInfo($order, true); // true that it is a stripe order
        $this->seeInDatabase('order_price_info', [
            'total_price' => 1, 'subtotal' => 2, 'stripe_fees' => 3, 'profit' => 4,
            'cost_of_food' => 5, 'delivery_fee' => 6, 'tax_charged' => 7, 'tax_percentage' => 8
        ]);
    }

    /**
     * @test
     */
    public function saveOrderPriceInfoForVenmoOnDemand()
    {
        $b = $this->mock(CartBilling::class);
        $b->shouldReceive([
            'getTotal' => 1, 'getSubtotal' => 2, 'getStripeFees' => 3, 'getProfit' => 4,
            'getCostOfFood' => 5, 'getDeliveryFee' => 6, 'getTax' => 7, 'getTaxPercent' => 8
        ]);
        // all we need is an order, the billing/cart stuff can be mocked
        $order = factory(Order::class)->create([
            'courier_types' => json_encode([CourierTypes::DRIVER]),
            'payment_type' => PaymentType::VENMO_PAYMENT,
            'order_types' => json_encode(['on_demand' => RestaurantOrderCategory::ON_DEMAND])
        ]);
        $c_order = new CustomerOrder(new ShoppingCart(), $b, []);
        $c_order->saveOrderPriceInfo($order, true); // true that it is a stripe order
        $this->seeInDatabase('order_price_info', [
            'total_price' => 1,
            'subtotal' => 2,
            'stripe_fees' => 3,
            'profit' => 4,
            'cost_of_food' => 5,
            'delivery_fee' => 6,
            'tax_charged' => 7,
            'tax_percentage' => 8
        ]);
    }
}
