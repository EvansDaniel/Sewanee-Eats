<?php

namespace App\Http\Controllers\Api;

use App\CustomTraits\CartInformation;
use App\Http\Controllers\Controller;

class CartInfoController extends Controller
{
    use CartInformation;

    public function quantity()
    {
        return json_encode(['num_items' => $this->getCartQuantity()]);
    }
}
