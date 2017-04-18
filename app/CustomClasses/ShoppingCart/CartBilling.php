<?php
/**
 * Created by PhpStorm.
 * User: blaise
 * Date: 4/1/17
 * Time: 3:59 PM
 */
namespace App\CustomClasses\ShoppingCart;


class CartBilling
{
    protected $cart;
    protected $special_billing;
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
    protected $cost_of_food;
    protected $discount;

    /**
     * CartBilling constructor.
     * @param $special_billing SpecialBilling the on demand billing object for the current session's cart
     * @param $on_demand_billing OnDemandBilling the specials billing object for the current session's cart
     */
    public function __construct(SpecialBilling $special_billing, OnDemandBilling $on_demand_billing)
    {
        // I KNOW YOU WON'T LISTEN BUT...
        // THE ORDER OF THE FUNCTIONS BELOW MATTER, THEY BUILD ON EACH
        // OTHER AND DO NOT PROVIDE ERROR CHECKING
        $this->on_demand_billing = $on_demand_billing;
        $this->special_billing = $special_billing;
        $this->tax_percent = 1.0925;
        $this->delivery_fee = $this->deliveryFee();
        $this->subtotal = $this->subtotal();
        $this->tax = $this->tax();
        $this->total = round($this->total(), 2);
        $this->stripe_fees = $this->stripeFees();
        $this->profit = $this->profit();

    }

    private function deliveryFee()
    {
        return $this->on_demand_billing->getDeliveryFee() + $this->special_billing->getDeliveryFee();
    }

    private function subtotal()
    {
        return $this->getCostOfFood() + $this->getDeliveryFee();
    }

    public function getCostOfFood()
    {
        return $this->on_demand_billing->getCostOfFood() + $this->special_billing->getCostOfFood();
    }

    /**
     * @return int
     */
    public function getDeliveryFee()
    {
        return $this->on_demand_billing->getDeliveryFee() + $this->special_billing->getDeliveryFee();
    }

    private function tax()
    {

        return ($this->getSubtotal() * $this->getTaxPercent()) - $this->getSubtotal();
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

    private function total()
    {
        return $this->getSubtotal() * $this->getTaxPercent();
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
        return $this->special_billing->getSpecialProfit() + $this->on_demand_billing->getOnDemandProfit();
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

    public function getDiscountString()
    {
        if ($this->getDiscount() < 1) {
            return $this->getDiscount() * 100 . " cents";
        }
        return $this->getDiscount() . " dollars";
    }

    /**
     * The total discount for on demand and special items as a percentage
     * @return int
     */
    public function getDiscount()
    {
        return $this->on_demand_billing->getDiscount() + $this->special_billing->getDiscount();
    }
}