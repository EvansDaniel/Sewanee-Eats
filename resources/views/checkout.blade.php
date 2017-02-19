@extends('layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/cart.css") }}">
    <title>Checkout</title>
    <style>
        #payment-form {
            padding: 10px;
        }
    </style>
    <meta id="x" content="{{ csrf_token() }}">
@stop

@section('body')
    <form action="{{ route('handleCheckout') }}" method="POST" id="payment-form">
        {{ csrf_field() }}
        <div class="container">
        @if(empty(Session::get('cart')) || Session::get('cart') == null)
            <!-- TODO: need better message here -->
                <h1>Not items in your cart</h1>
                <a href="{{ route('list_restaurants') }}">Start your order here</a>
            @else
                <h1>Your Food</h1>
                <ul.cart>
                    <!-- Loop through all menu items in the cart -->
                    @foreach(Session::get('cart') as $order)
                        <li.cart>
                            @for($i = 0; $i < $order['quantity']; $i++)
                                <div class="menu-item container" id="mid-{{$order['menu_item_model']->id}}">
                                    <div class="row">
                                        <div><h3>{{ $order['menu_item_model']->name }}</h3></div>
                                        <div>{{ $order['menu_item_model']->price }}</div>
                                        <div>{{ $order['menu_item_model']->description }}</div>
                                    </div>
                                    <input type="hidden" name="cart_item_id"
                                           value="{{ $order['menu_item_model']->id }}">
                                    <div class="row">
                                        @if(empty($order['special_instructions'][$i]))
                                            <button type="button" class="checkout-btn" onclick="showInstruction(this)">
                                                Add Special Instructions
                                            </button>
                                            <div style="display: none;">
                                            @else <!-- Make the div hidden or not -->
                                                <div>
                                                    @endif
                                                    <label for="si-id">Special
                                                        instructions</label>
                                                    <div class="container row ">
                                               <textarea id="si-id-{{$order['menu_item_model']->id}}-{{$i}}"
                                                         class="si"
                                                         data-model-id="{{$order['menu_item_model']->id}}"
                                                         data-index="{{$i}}"
                                                         name="special_instructions">{{ $order['special_instructions'][$i] }}</textarea>
                                                    </div>
                                                    <input name="special_instructions" type="hidden"
                                                           value="{{ $order['special_instructions'][$i] }}">
                                                </div>

                                            </div>
                                            <div class="row">
                                                <br><br>
                                                @if(empty($order['extras'][$i]))
                                                    <div class="extras">
                                                        <button type="button" onclick="showExtras(this)"
                                                                class="btn btn-primary">
                                                            Add extras
                                                        </button>
                                                        <div style="display: none">
                                                            @else
                                                                <div>
                                                                    @endif
                                                                    <label for="extras">Select items accessories</label>
                                                                    @foreach($order['menu_item_model']->accessories as $acc)
                                                                        <div>
                                                                            <div class="checkbox">
                                                                                <label for="acc">
                                                                                    @if(in_array($acc->id,$order['extras'][$i]))
                                                                                        <input id="acc-{{$i}}-{{$acc->id}}"
                                                                                               name="extras[]"
                                                                                               type="checkbox"
                                                                                               data-model-id="{{$order['menu_item_model']->id}}"
                                                                                               data-index="{{$i}}"
                                                                                               checked class="acc-check"
                                                                                               value="{{ $acc->id }}">
                                                                                        {{ $acc->name . "  $" . $acc->price }}
                                                                                    @else
                                                                                        <input id="acc-{{$i}}-{{$acc->id}}"
                                                                                               name="extras[]"
                                                                                               type="checkbox"
                                                                                               class="acc-check"
                                                                                               data-model-id="{{$order['menu_item_model']->id}}"
                                                                                               data-index="{{$i}}"
                                                                                               value="{{ $acc->id }}">
                                                                                        {{ $acc->name . "  $" . $acc->price }}
                                                                                    @endif
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                        </div>
                            @endfor
                        </li.cart>
                        <hr>
                    @endforeach
                </ul.cart>
            @endif
        </div>
        <!-- Show checkout info only if we are on the checkout page -->
        @if(!empty(Session::get('cart')) || Session::get('cart') == null)
            <div class="cart">
                <!-- Payment information -->
                <h4>Enter your information to pay:</h4>
                <span class="payment-errors"></span>
                <div class="form-row">
                    <label>
                        <span>Card Number</span>
                        <input type="text" size="20" data-stripe="number">
                    </label>
                </div>

                <div class="form-row">
                    <label>
                        <span>Expiration (MM/YY)</span>
                        <input type="text" size="2" data-stripe="exp_month">
                    </label>
                    <span> / </span>
                    <input type="text" size="2" data-stripe="exp_year">
                </div>

                <div class="form-row">
                    <label>
                        <span>CVC</span>
                        <input type="text" size="4" data-stripe="cvc">
                    </label>
                </div>
                <label for="location">Where should we deliver the food?</label>
                <input type="text" name="location" id="location" required>
                <label for="phone-number">Please enter your phone number</label>
                <input type="tel" name="phone_number" id="phone-number" required>
                <div style="color:red">TODO: compute the delivery time in the back end</div>
                <div>Expected delivery time: 12:30pm</div>
                <div>Subtotal: {{ $cost_before_fees }}</div>
                <div>Order Total: ${{ $total_price }}</div>
                <button type="submit" id="pay-now-button" onclick="checkPayNow(event)" class="checkout-btn">Pay Now
                </button>
            </div>
        @endif
    </form>

    <!-- End payment information -->


    <div class="container">

    </div>

    <!-- Strip payment script -->
    <script>
        /*Stripe.setPublishableKey('pk_test_GALLn3YWDPqPycDBzdxuMz2z');

         $(function () {
         var $form = $('#payment-form');
         $form.submit(function (event) {
         // Disable the submit button to prevent repeated clicks:
         $form.find('.submit').prop('disabled', true);

         // Request a token from Stripe:
         Stripe.card.createToken($form, stripeResponseHandler);

         // Prevent the form from being submitted:
         return false;
         });
         });

         function stripeResponseHandler(status, response) {
         // Grab the form:
         var $form = $('#payment-form');

         if (response.error) { // Problem!

         // Show the errors on the form:
         $form.find('.payment-errors').text(response.error.message);
         $form.find('.submit').prop('disabled', false); // Re-enable submission

         } else { // Token was created!

         // Get the token ID:
         var token = response.id;

         // Insert the token ID into the form so it gets submitted to the server:
         $form.append($('<input type="hidden" name="stripeToken">').val(token));

         // Submit the form:
         $form.get(0).submit();
         }
         }*/

    </script>

    </div>
    <script src="{{ asset('js/checkout.js') }}"></script>
@stop
