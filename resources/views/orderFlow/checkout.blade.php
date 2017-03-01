@extends('layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/cart.css",env('APP_ENV') === 'production') }}">
    <title>Checkout</title>
    <style>
        #payment-form {
            padding: 10px;
        }
    </style>
    <meta id="x" content="{{ csrf_token() }}">
@stop

@section('body')
    <div class="container-fluid" id="cart-container">
        <form action="{{ url()->to(parse_url(route('handleCheckout',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
              method="post" id="payment-form">
            {{--<form action="{{ route('handleCheckout') }}" method="POST" id="payment-form">--}}
            {{ csrf_field() }}
            @if(empty(Session::get('cart')) || Session::get('cart') == null)
                <h1>Your Food</h1>
                <hr>
                <div id="cart-title" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="c-t-i">
                        <h1>You don't have any items in your cart!</h1>
                    </div>
                    <div class="row">
                        <a id="cart-order-again" href="{{ route('list_restaurants') }}">Start your order here</a>
                    </div>
                </div>
            @else
                <h1 id="cart-title" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    Thank you for shopping with us. This is your order summary,
                </h1>
                <h3 class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cart-review">
                    1.Review item(s)
                </h3>
                <hr>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="orders">

                    <!-- Loop through all menu items in the cart -->
                    @foreach(Session::get('cart') as $order)

                        @for($i = 0; $i < $order['quantity']; $i++)
                            <div class="menu-item row" id="mid-{{$order['menu_item_model']->id}}">

                                <div class="col-lg-2 col-md-2 order-name"><p>{{ $order['menu_item_model']->name }}</p>
                                </div>
                                <div class="col-lg-2 col-md-2 order-price">
                                    $ {{ $order['menu_item_model']->price }}</div>
                                <div class="col-lg-2 col-md-2 order-descr">{{ $order['menu_item_model']->description }}</div>

                                <input type="hidden" name="cart_item_id" value="{{ $order['menu_item_model']->id }}">
                                <div class="col-lg-3 col-md-3 order-special">
                                    @if(empty($order['special_instructions'][$i]))
                                        <button type="button" class="checkout-btn"
                                                onclick="showInstruction(this)">
                                            Add Special Instructions
                                        </button>
                                @endif <!-- Make the div hidden or not -->
                                    <div style="display: {{ empty($order['special_instructions'][$i]) ? "none" : "block"}};">
                                        <label for="si-id" id="special-btn">Special
                                            instructions</label>
                                        <div class="container row">
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


                                <div class="col-lg-2 col-md-2">
                                    @if(empty($order['extras'][$i]))
                                        <button type="button"
                                                onclick="showExtras(this)"
                                                class="btn btn-primary show-extra">
                                            Add extras
                                        </button>
                                    @endif
                                    <div class="row"
                                         style="display: {{ empty($order['extras'][$i]) ? "none" : "block"}};">
                                        <label for="extras">Select items accessories</label>
                                        @foreach($order['menu_item_model']->accessories as $acc)
                                            <div class="checkbox">
                                                <label for="acc">
                                                    @if(!(empty($order['extras'][$i])) && in_array($acc->id,$order['extras'][$i]))
                                                        <input id="acc-{{$i}}-{{$acc->id}}"
                                                               name="extras{{$i}}[]"
                                                               type="checkbox"
                                                               data-model-id="{{$order['menu_item_model']->id}}"
                                                               data-index="{{$i}}"
                                                               checked class="acc-check"
                                                               value="{{ $acc->id }}">
                                                        {{ $acc->name . "  $" . $acc->price }}
                                                    @else
                                                        <input id="acc-{{$i}}-{{$acc->id}}"
                                                               name="extras{{$i}}[]"
                                                               type="checkbox"
                                                               class="acc-check"
                                                               data-model-id="{{$order['menu_item_model']->id}}"
                                                               data-index="{{$i}}"
                                                               value="{{ $acc->id }}">
                                                        {{ $acc->name . "  $" . $acc->price }}
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                                <div class="col-lg-1 col-md-1">
                                    <button class="ckbtn btn btn-primary"
                                            id="dfc-{{ $order['menu_item_model']->id }}-{{ $i }}"
                                            data-model-id="{{ $order['menu_item_model']->id }}"
                                            data-item-index="{{ $i }}"
                                            onclick="deleteItemFromCart(this)"
                                            type="button">X
                                    </button>
                                </div>
                            </div>
                            <hr class="cart-line">
                        @endfor

                    @endforeach

                </div>
            @endif
            @if(!empty(Session::get('cart')) && Session::get('cart') != null)

                <div class="cart col-lg-12 col-md-12 col-sm-12 col-xs-12" id="main-payment-form" style="display: none;">
                    <!-- Payment information -->
                    <h4>Enter your information to pay:</h4>
                    <span class="" style="display: none" id="payment-errors"></span>
                    <div class="form-group">
                        <label>
                            <span>Card Number</span>
                            <input class="pay-input form-control" type="text" id="card-number" size="20"
                                   data-stripe="number">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>
                            <span>Expiration (MM/YY)</span>
                            <input class="pay-input" type="text" size="2" id="exp-month" maxlength="2"
                                   data-stripe="exp_month">
                        </label>
                        {{--<span> / </span>--}}
                        <input class="pay-input" type="text" size="4" id="exp-year" maxlength="4"
                               data-stripe="exp_year">
                    </div>

                    <div class="form-group">
                        <label>
                            <span>CVC</span>
                            <input class="pay-input" type="text" size="4" maxlength="4" id="cvc" data-stripe="cvc">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="location">Where should we deliver the food?</label>
                        <input class="form-control pay-input" type="text" name="location" id="location">
                        <label for="phone-number">Please enter your phone number</label>
                        <input class="form-control pay-input" maxlength="10" placeholder="10 digits, only numbers"
                               type="tel"
                               name="phone_number" id="phone-number">
                    </div>
                    <div style="color:red">TODO: compute the delivery time in the back end</div>
                    <div>Expected delivery time: 12:30pm</div>
                    <div>Subtotal: $<span id="subtotal">{{ $subtotal }}</span></div>
                    <div>Order Total: $<span id="total-price">{{ $total_price }}</span></div>
                    <button type="submit" id="pay-now-button" onclick="checkPayNow(event)" class="checkout-btn">Pay Now
                    </button>
                </div>
        @endif

        <!-- Show checkout info only if we are on the checkout page -->

        </form>
    </div>
    <!-- End payment information -->

    <!-- Strip payment script -->
    <script>
        Stripe.setPublishableKey('pk_test_GALLn3YWDPqPycDBzdxuMz2z');

        $(function () {
            var $form = $('#payment-form');
            $form.submit(function (event) {
                // Disable the submit button to prevent repeated clicks:
                $form.find('.submit').prop('disabled', true);

                /*var message = validPayForm(true);
                 if (message !== null) { // an error message was returned
                 $('#payment-errors').show().text(message);
                 event.preventDefault();
                 $form.find('.submit').prop('disabled', false);
                 return false;
                 }*/

                // Request a token from Stripe:
                Stripe.card.createToken($form, stripeResponseHandler);

                // Prevent the form from being submitted:
                event.preventDefault();
                return false;
            });
        });

        function stripeResponseHandler(status, response) {
            // Grab the form:
            var $form = $('#payment-form');

            p('in response handler');
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
        }

    </script>
    <script src="{{ asset('js/checkout.js',env('APP_ENV') === 'production') }}"></script>
@stop
