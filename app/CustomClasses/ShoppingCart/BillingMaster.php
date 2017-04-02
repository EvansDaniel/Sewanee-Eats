<?php
/**
 * Created by PhpStorm.
 * User: blaise
 * Date: 4/1/17
 * Time: 3:59 PM
 */
namespace App\CustomClasses\ShoppingCart;
use App\CustomClasses\ShoppingCart\WeeklyBilling;
use App\CustomClasses\ShoppingCart\OnDemandBilling;


use App\Models\Accessory;

class BillingMaster
{
    protected $cart;
    protected $weekly_item;
    protected $on_demand_item;
    protected $weekly_cost;
    protected $on_demand_cost;
    protected $subtotal;
    protected $total;
    protected $tax;
    protected $tax_percent;
    protected $stripe_fees;
    protected $profit;
    protected $delivery_fee;

    /**
     * CartBilling constructor.
     * @param ShoppingCart|null $cart For displaying business info, like prices, no need to pass a shopping cart
     * else pass the shopping cart to compute billing information
     */
    public function __construct(ShoppingCart $cart = null)
    {
        $this->cart = $cart;
        $this->weekly_item = new WeeklyBilling($cart);
        $this->on_demand_item = new OnDemandBilling($cart);
        $this->tax_percent = 1.0925;
        $this->weekly_cost= $this->weeklyCost();
        $this->on_demand_cost = $this->onDemandCost();
        $this->subtotal = $this->costOfFood();
        // I KNOW YOU WON'T LISTEN BUT...
        // THE ORDER OF THE FUNCTIONS BELOW MATTER, THEY BUILD ON EACH
        // OTHER AND DO NOT PROVIDE ERROR CHECKING
        $this->tax = $this->tax();
        $this->total = round($this->totalPrice(), 2);
        $this->stripe_fees = $this->stripeFees();
        $this->profit = $this->profit();
    }

    public function onDemandCost()
    {
        $demand = $this->on_demand_item;
        $base = $demand->getDeliveryFee();
        $n = $demand-> getNumberWithoutFeeItems();
        $bonus = $demand->getExtraFee();

        return ($base + ($bonus * $n));
    }
    private function weeklyCost(){
        $weekly = $this->weekly_item;
        $n = $weekly->getNumberOfItems();
        $base = $weekly->getDeliveryFee();
        $iwd = $weekly->countItemsWithDiscount();
        $dnt = $weekly->getDiscount();
        $m = $weekly->getMarkup();

        return ($base - ($dnt * $iwd) + ($n*$m));
    }

    private function deliveryFee()
    {

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
        $cost_of_food = $this->weeklyCost() + $this->onDemandCost();
        $cost_of_accessories = 0;

        if (!empty($this->cart->items())){
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
        return $this->getCostOfFood();
    }

    /**

     * @return mixed
     */
    public function getDeliveryFee()
    {

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