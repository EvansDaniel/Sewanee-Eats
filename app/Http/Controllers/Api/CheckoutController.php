<?php

namespace App\Http\Controllers\Api;

use App\CustomTraits\CartInformation;
use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Session;

class CheckoutController extends Controller
{
    use CartInformation;

    public function updateSpecialInstructionForItem(Request $request,
                                                    $model_id,
                                                    $si_index)
    {
        // names: special_instruction
        $cart = Session::get('cart');
        if (empty($cart))
            return null;

        $instruction = $request->input('special_instructions');
        $size = count($cart);
        for ($i = 0; $i < $size; $i++) {
            if ($cart[$i]['menu_item_model']->id == $model_id) {
                // TODO: save this back to the session cart
                $cart[$i]['special_instructions'][$si_index] = $instruction;
                Session::put('cart', $cart);
                break;
            }
        }
        return json_encode($instruction);
    }

    public function updateAccessoryForItem(Request $request,
                                           $model_id,
                                           $extras_index)
    {
        // check if item exists
        $acc_id = $request->input('accessory');
        $cart = Session::get('cart');
        if (empty($cart))
            return null;
        if (empty($acc_id))
            return null;
        if (empty($model_id))
            return null;

        $acc = Accessory::find($acc_id);
        // get first acc_id from request
        $saved_index = -1;
        foreach ($cart as $cart_item_index => $cart_item) {
            if ($model_id == $cart_item['menu_item_model']->id) {
                \Log::info('in model id');
                $saved_index = $cart_item_index;
                foreach ($cart[$cart_item_index]['extras'][$extras_index] as $extra_index_id => $extra_id) {
                    if ($extra_id == $acc->id) {
                        \Log::info('in extra id');
                        unset($cart[$cart_item_index]['extras'][$extras_index][$extra_index_id]);
                        $cart[$cart_item_index]['extras'][$extras_index] =
                            array_values($cart[$cart_item_index]['extras'][$extras_index]);
                        Session::put('cart', $cart);
                        return $acc;
                    }
                }
            }
        }
        // a model with id == $model_id was not found
        if ($saved_index == -1) {
            return null;
        }

        // accessory wasn't found so add this accessory
        $cart[$saved_index]['extras'][$extras_index][] = $acc->id;
        Session::put('cart', $cart);
        return json_encode($acc);
    }

    public function getCheckoutItem($id)
    {
        $cart = Session::get('cart');
        if (empty($cart)) {
            return json_encode(null);
        }
        foreach ($cart as $item) {
            if ($item['menu_item_model']->id == $id)
                return json_encode($item);
        }
        return json_encode(null);
    }
}
