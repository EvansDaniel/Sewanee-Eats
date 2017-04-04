<?php
/**
 * Created by PhpStorm.
 * User: blaise
 * Date: 4/1/17
 * Time: 3:59 PM
 */
namespace App\CustomClasses\ShoppingCart;
use App\Models\Accessory;


class CartBilling
{
    protected $cart;
    protected $weekly_special_billing;
    protected $on_demand_billing;
    protected $weekly_cost;
    protected $on_demand_cost;
    protected $subtotal;
    protected $total;
    protected $tax;
    protected $tax_percent;
    protected $stripe_fees;
    protected $profit;
    protected $delivery_fee;
    protected $total_price;
    protected $cost_of_food;
    protected $discount;

    /**
     * CartBilling constructor.
     * @param ShoppingCart|null $cart For displaying business info, like prices, no need to pass a shopping cart
     * else pass the shopping cart to compute billing information
     */
    public function __construct(ShoppingCart $cart = null)
    {
        $this->cart = $cart;
        $this->weekly_special_biliing = new WeeklyBilling($cart);
        $this->on_demand_biliing = new OnDemandBilling($cart);
        $this->tax_percent = 1.0925;
        $this->weekly_cost= $this->weeklyCost();
        $this->on_demand_cost = $this->onDemandCost();
        $this->cost_of_food = $this->costOfFood();
        $this->delivery_fee = $this->deliveryFee();
        $this->subtotal = $this->subtotal();
        // I KNOW YOU WON'T LISTEN BUT...
        // THE ORDER OF THE FUNCTIONS BELOW MATTER, THEY BUILD ON EACH
        // OTHER AND DO NOT PROVIDE ERROR CHECKING
        $this->tax = $this->tax();
        $this->total = round($this->totalPrice(), 2);
        $this->stripe_fees = $this->stripeFees();
        $this->profit = $this->profit();

    }

    private function weeklyCost(){


        return $this->weekly_special_biliing->getCostOfWeekly();
    }

    public function onDemandCost()
    {
        return $this->on_demand_biliing->getOnDemandCost();
    }

    public function costOfFood()
    {
        $cost_of_food = $this->weekly_cost + $this->on_demand_cost;
        $cost_of_accessories = 0;

        if (!empty($this->cart->items())){
            foreach ($this->cart->items() as $cart_item) {
                if (!empty($cart_item->getExtras())) {
                    foreach ($cart_item->getExtras() as $extra_id) {
                        $acc = Accessory::find($extra_id);
                        if (empty($acc)) {
                            \Log::info('tried to access accessory that didn\'t exist at line: ' . __LINE__ . ' in function ' . __FUNCTION__);
                            continue;
                        }
                        $cost_of_accessories += $acc->price;
                    }
                }
            }
        }

        return $cost_of_food + $cost_of_accessories;
    }

    private function deliveryFee()
    {
        return $this->on_demand_biliing->getFeeAfter() + $this->weekly_special_biliing->getFeeAfter();
    }

    private function subtotal()
    {
        return $this->getCostOfFood() + $this->deliveryFee();
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
        return $this->weekly_special_biliing->getWeeklyProfit() + $this->on_demand_biliing->getOnDemandProfit();
    }

    /**
     * @return int
     */
    public function getDeliveryFee()
    {
        return $this->delivery_fee;
    }

    /**
     * @return ShoppingCart|null
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @return \App\CustomClasses\ShoppingCart\WeeklyBilling
     */
    public function getWeeklyItem()
    {
        return $this->weekly_special_biliing;
    }

    /**
     * @return \App\CustomClasses\ShoppingCart\OnDemandBilling
     */
    public function getOnDemandItem()
    {
        return $this->on_demand_biliing;
    }

    /**
     * @return mixed
     */
    public function getWeeklyCost()
    {
        return $this->weekly_cost;
    }

    /**
     * @return int
     */
    public function getOnDemandCost()
    {
        return $this->on_demand_cost;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->total_price;
    }

    public function getStripeFees($is_stripe_order)
    {
        if ($is_stripe_order) {
            return $this->stripe_fees;
        }
        return 0;
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

    public function discount()
    {
        return $this->weekly_special_biliing->getDiscount();
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }


}