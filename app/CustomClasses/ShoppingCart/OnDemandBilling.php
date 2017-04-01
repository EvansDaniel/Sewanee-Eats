<?php
/**
 * Created by PhpStorm.
 * User: blaise
 * Date: 4/1/17
 * Time: 3:57 PM
 */

namespace App\CustomClasses\ShoppingCart;


use App\Models\Accessory;

class OnDemandBilling
{
    protected $cart;
    protected $delivery_fee;
    protected $discount;
    protected $subtotal;
    protected $delivery_fee_reduction;
    protected $total;
    protected $tax;
    protected $tax_percent;
    protected $base_delivery_fee;
    protected $cost_of_food;
    protected $mark_up_per_item;
    protected $stripe_fees;
    protected $profit;
    protected $number_of_items;

    /**
     * CartBilling constructor.
     * @param ShoppingCart|null $cart For displaying business info, like prices, no need to pass a shopping cart
     * else pass the shopping cart to compute billing information
     */
    public function __construct(ShoppingCart $cart = null)
    {
        $this->cart = $cart;
        $this->number_of_items = $this->cart->countOnDemandItems();
        $this->tax_percent = 1.0925;
        $this->base_delivery_fee = 3;
        $this->mark_up_per_item = .75;
        $this->delivery_fee_reduction = .6;
        // I KNOW YOU WON'T LISTEN BUT...
        // THE ORDER OF THE FUNCTIONS BELOW MATTER, THEY BUILD ON EACH
        // OTHER AND DO NOT PROVIDE ERROR CHECKING
        $this->delivery_fee = $this->deliveryFee();
        $this->discount = $this->discount();
        $this->cost_of_food = $this->costOfFood();
        $this->subtotal = $this->subtotal();
        $this->tax = $this->tax();
        $this->total = round($this->totalPrice(), 2);
        $this->stripe_fees = $this->stripeFees();
        $this->profit = $this->profit();
    }

    private function deliveryFee()
    {
        $num_items = $this->cart->getQuantity();
        if ($num_items >= 4) {
            return ($this->getBaseDeliveryFee() - ($this->getDeliveryFeeReduction() * 3));
        }
        /*
         * If the $num_items = 0, then when we subtract 1 it will become negative
         * The absolute value of that will be greater than the original $num_items
         * (which is zero)
         */
        return $this->getBaseDeliveryFee() -
            ($this->getDeliveryFeeReduction() * (min(abs($num_items - 1), $num_items)));
    }

    /**
     * @return int
     */
    public function getBaseDeliveryFee()
    {
        return $this->base_delivery_fee;
    }


    public function getDeliveryFeeReduction()
    {
        return $this->delivery_fee_reduction;
    }

    private function discount()
    {
        $num_items = $this->cart->getQuantity();
        if ($num_items >= 3) {
            return 60;
        }
        return ($num_items - 1) * 20;
    }

    public function costOfFood()
    {
        $cost_of_food = 0;
        $cost_of_accessories = 0;
        if (!empty($this->cart->items())) {
            foreach ($this->cart->items() as $cart_item) {
                $cost_of_food += $cart_item->getPrice();
            }
            foreach ($this->cart->items() as $cart_item) {
                if (!empty($cart_item->getExtras())) {
                    foreach ($cart_item->getExtras() as $extra_id) {
                        $cost_of_accessories += Accessory::find($extra_id)->price;
                    }
                }
            }
        }
        return $cost_of_food + $cost_of_accessories;
    }

    private function subtotal()
    {
        return $this->getDeliveryFee() + $this->getCostOfFood();
    }

    /**

     * @return mixed
     */
    public function getDeliveryFee()
    {
        return $this->delivery_fee;
    }

    public function getCostOfFood()
    {
        return $this->cost_of_food;
    }

    private function tax()
    {
        return 0;
    }

    private function totalPrice()
    {
        return $this->getSubtotal() * $this->getTaxPercent();
    }

    /**
     * @return mixed
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @return float
     */
    public function getTaxPercent()
    {
        return $this->tax_percent;
    }

    public function stripeFees()
    {
        // stripe charges 30 cents + 2.9% -> .029
        return .3 + $this->getTotal() * .029;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    public function profit()
    {
        // profit per order is the calculated delivery fee
        // plus the mark up on each item * num items
        // minus expenses i.e. stripe fees
        return $this->getDeliveryFee() +
            ($this->cart->getQuantity() * $this->getMarkUpPerItem())
            - $this->getStripeFees();
    }

    /**
     * @return float
     */
    public function getMarkUpPerItem()
    {
        return $this->mark_up_per_item;
    }

    public function getStripeFees()
    {
        return $this->stripe_fees;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    public function getProfit()
    {
        return $this->profit;
    }


}