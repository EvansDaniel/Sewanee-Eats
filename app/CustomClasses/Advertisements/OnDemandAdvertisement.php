<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/18/17
 * Time: 3:43 PM
 */

namespace App\CustomClasses\Advertisements;


use App\CustomClasses\Schedule\Shift;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Models\Restaurant;

class OnDemandAdvertisement
{
    protected $cart;
    protected $items_added;

    public function __construct()
    {

    }

    public static function withCart(ShoppingCart $cart, $items = null)
    {
        $instance = new OnDemandAdvertisement();
        $instance->cart = $cart;
        $instance->items_added = $items;
        return $instance;
    }

    public function getAdvertisementMsg()
    {
        if (!empty($this->cart)) {
            return $this->getCartMessage();
        }
    }

    private function getCartMessage()
    {
        if (!$this->cart->hasSpecialItems() && Restaurant::availableToCWeeklySpecial()->count() > 0) {
            return $this->linkToSpecials() . '<br>' . $this->getCheckoutButton();
        } else if (!$this->cart->hasOnDemandItems() && Shift::onDemandIsAvailable()) {
            return $this->linkToOnDemand() . '<br>' . $this->getCheckoutButton();
        }
        return $this->getCheckoutButton();
    }

    private function linkToSpecials()
    {
        // TEMPORARY MESSAGE TO USERS ABOUT SPECIALS TO PROMOTE SPARTY, uncomment below later
        $name = 'Chick Fil A Sparty Breakfast';
        return "Gearing up for Sparty? Check out our <a href='" . route('showMenu', ['name' => cleanseRestName($name)]) . "#special-rests'>Sparty breakfast special</a>";
        //return "Be sure check out <a href='" . route('list_restaurants') . "#special-rests'>this week's specials</a>";
    }

    private function getCheckoutButton()
    {
        return '
            <a href="' . route("checkout") . '">
                <button class="btn btn-primary"
                        style="background-color: rebeccapurple; margin-top: 1%">Proceed to Checkout</button>
            </a>';
    }

    private function linkToOnDemand()
    {
        return "Feeling hungry now? We'll deliver for you! Check out our <a href='" . route('list_restaurants') . "#on-demand'>On-Demand service</a>";
    }

    private function genericAddedMessage()
    {
        return "The item has been added to the cart. ";
    }
}