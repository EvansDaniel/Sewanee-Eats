<?php

namespace App\Http\Controllers\Api;

use App\CustomTraits\PriceInformation;
use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Session;

class CheckoutController extends Controller
{
    use PriceInformation;

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
        if (empty($cart) || empty($acc_id) || empty($model_id))
            return response(null, 400);

        $acc = Accessory::find($acc_id);
        // get first acc_id from request
        $saved_index = -1;
        foreach ($cart as $cart_item_index => $cart_item) {
            if ($model_id == $cart_item['menu_item_model']->id) {
                $saved_index = $cart_item_index;
                if (!empty($cart[$cart_item_index]['extras'][$extras_index])) {
                    foreach ($cart[$cart_item_index]['extras'][$extras_index] as $extra_index_id => $extra_id) {
                        if ($extra_id == $acc->id) {
                            unset($cart[$cart_item_index]['extras'][$extras_index][$extra_index_id]);
                            $cart[$cart_item_index]['extras'][$extras_index] =
                                array_values($cart[$cart_item_index]['extras'][$extras_index]);
                            Session::put('cart', $cart);
                            return $this->getPriceResponse();
                        }
                    }
                } else {
                    break;
                }
            }
        }
        // a model with id == $model_id was not found
        if ($saved_index == -1) {
            return response(null, 400);
        }

        // accessory wasn't found so add this accessory
        $cart[$saved_index]['extras'][$extras_index][] = $acc->id;
        Session::put('cart', $cart);
        return $this->getPriceResponse();
    }

    private function getPriceResponse()
    {
        /*$subtotal = $this->getSubTotal();
        $response = [
            'totalPrice' => $this->getTotalPrice($subtotal),
            'subtotal' => $subtotal
        ];*/
        $price_summary = $this->getPriceSummary();
        return response($price_summary, 200);
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
        return response(json_encode([
            'error' => 'The requested item was not found '
        ]), 400);
    }

    /**
     * @param $model_id
     * @param $item_index
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * returns the new total price and subtotal
     */
    public function deleteFromCart($model_id, $item_index)
    {
        $cart = Session::get('cart');
        // TODO: perform checks on the item_index
        if (empty($cart) || empty($model_id)) {
            response(null, 400);
        }
        $cart_item_index = -1;
        foreach ($cart as $cti => $cart_item) {
            if ($model_id == $cart_item['menu_item_model']->id) {
                $cart_item_index = $cti;
            }
        }
        if ($cart_item_index == -1) {
            \Log::info('Trying to delete something from the cart that doesn\'t exist ' .
                'model_id = ' . $model_id . ' item_index = ' . $item_index);
            response(null, 400);
        }
        // unset all extras, the
        unset($cart[$cart_item_index]['extras'][$item_index]);
        unset($cart[$cart_item_index]['special_instructions'][$item_index]);
        $cart[$cart_item_index]['quantity']--;
        if ($cart[$cart_item_index]['quantity'] == 0) {
            // Delete model as well
            unset($cart[$cart_item_index]);
        } else {
            // normalize array values after unset
            $cart[$cart_item_index]['extras'] = array_values($cart[$cart_item_index]['extras']);
            $cart[$cart_item_index]['special_instructions'] = array_values($cart[$cart_item_index]['special_instructions']);
        }
        // save cart back to session
        Session::put('cart', $cart);
        return $this->getPriceResponse();
    }
}
