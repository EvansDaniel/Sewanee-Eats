<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/7/17
 * Time: 11:41 AM
 */

namespace App\CustomClasses\ShoppingCart;


use App\Contracts\HasItems;

class ItemLister
{

    protected $container;
    protected $order_special_rest_buckets;
    protected $order_special_rest_buckets_id;
    protected $cart_special_rest_buckets;

    public function __construct(HasItems $container)
    {
        $this->container = $container;
    }

    public function toWeeklySpecialRestBuckets()
    {
        $rest_buckets = [];
        foreach ($this->container->items() as $item) {
            \Log::info(' the item that has restaurant ' . $item);
            \Log::info('items retaurant ' . $item->getSellerEntity());
            \Log::info('restaurant seller type in special rest buckets ' . $item->getSellerEntity()->seller_type);
            if ($item->getSellerEntity()->isSellerType(RestaurantOrderCategory::WEEKLY_SPECIAL)) {
                $rest_buckets[$item->getSellerEntity()->id][] = $item;
            }
        }
        \Log::info('rest buckets ');
        \Log::info($rest_buckets);
        return $rest_buckets;
    }

    public function getOnDemandOrderItems()
    {
        $on_demand_items = [];
        foreach ($this->container->items() as $item) {
            if ($item->getSellerEntity()->isSellerType(RestaurantOrderCategory::ON_DEMAND)) {
                $on_demand_items[] = $item;
            }
        }
        return $on_demand_items;
    }
}