<?php

namespace App\CustomClasses\ShoppingCart;


use App\Contracts\Availability;
use App\Models\EventItem;
use App\Models\MenuItem;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Class CartItem Client interface for items in the shopping cart
 * @package App\CustomClasses
 */
class CartItem implements Availability
{
    protected $item;
    protected $extras;
    protected $si;
    protected $item_type;
    protected $cart_item_id;

    public function __construct($item_id, $item_type)
    {
        $this->item_type = $item_type;
        if ($item_type == ItemType::EVENT_ITEM) {
            $this->item = EventItem::find($item_id);
        } else if ($item_type == ItemType::RESTAURANT_ITEM) {
            $this->item = MenuItem::find($item_id);
        }
        if (empty($this->item)) {
            throw new InvalidArgumentException('Item associated with $item_id passed to CartItem constructor could not be found');
        }
        $this->si = "";
        $this->extras = [];
    }

    public function isSellerType($seller_type)
    {
        return $this->getSellerEntity()->getSellerType() == $seller_type;
    }

    public function getSellerEntity()
    {
        return $this->item->getSellerEntity();
    }

    public function getId()
    {
        \Log::info($this->item->id);
        return $this->item->id;
    }

    public function __toString()
    {
        return json_encode($this->item);
    }

    /**
     * @return mixed
     */
    public function getItemType()
    {
        return $this->item_type;
    }

    /**
     * @return mixed
     */
    public function getExtras()
    {
        return $this->extras;
    }

    public function setExtras($extras)
    {
        $this->extras = $extras;
    }

    /**
     * @return mixed
     */
    public function getSi()
    {
        return $this->si;
    }

    public function setInstructions($si)
    {
        $this->si = $si;
    }

    public function itemExtras()
    {
        return $this->item->extras();
    }

    public function getCartItemId()
    {
        return $this->cart_item_id;
    }

    public function setCartItemId($cart_id)
    {
        $this->cart_item_id = $cart_id;
    }

    public function itemType()
    {
        return $this->item_type;
    }

    public function getName()
    {
        return $this->item->getName();
    }

    public function getDesc()
    {
        return $this->item->getDesc();
    }

    public function getPrice()
    {
        return $this->item->getPrice();
    }

    public function getAvailability()
    {
        return $this->item->getAvailability();
    }

    /**
     * @return integer the extra time to check before it actually closes
     * i.e. the cushion period
     */
    public function getExtraTime()
    {
        return 30; // extra 30 minutes
    }
}