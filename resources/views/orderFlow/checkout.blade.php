@extends('main.main_layout')

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
    <script src="{{ asset('js/Checkout/checkout_animations.js',env('APP_ENV') === 'production') }}"></script>
    <div class="container-fluid cart-container" id="cart-container">
        <form action="{{ url()->to(parse_url(route('handleCheckout',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
              method="post" id="payment-form">
            {{--<form action="{{ route('handleCheckout') }}" method="POST" id="payment-form">--}}
            {{ csrf_field() }}
            @if(empty(Session::get('cart')) || Session::get('cart') == null)
                <h2>Your Food</h2>
                <hr style="color: rebeccapurple">
                <div id="cart-title" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="c-t-i">
                        <h2>You don't have any items in your cart!</h2>
                    </div>
                    <div class="row">
                        <a id="cart-order-again" href="{{ route('list_restaurants') }}">Start your order here</a>
                    </div>
                </div>
            @else
                <h1 id="cart-title" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    Thank you for shopping with us. This is your order summary,
                </h1>
                <div class="row h3">
                    <h3 class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cart-review rev1">
                        Review Order
                    </h3>
                    <hr class="cart-sep sep1">
                </div>


                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="orders">

                    @if(!empty($cart->getOnDemandItems()))
                        <h3 class="type-title">Your On Demand Items</h3>
                        <h4 class="estimated-time"><i>Estimated Delivery Time: <span
                                        id="on-demand-delivery-time"></span> mins</i></h4>
                        <hr class="hr-separator">
                        @foreach($cart->getOnDemandItems() as $order)
                            @include('partials.checkout_items')
                        @endforeach
                        <hr class="hr-separator">
                    @endif

                    @if(!empty($cart->getWeeklySpecialItems()))
                        <h3 class="type-title">Your Weekly Special Items</h3>
                        <hr class="hr-separator">
                        @foreach($cart->getWeeklySpecialItems() as $order)
                            @include('partials.checkout_items')
                        @endforeach
                        <hr class="hr-separator">
                    @endif

                    <hr class="cart-line">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="orders">
                        <!-- Loop through all menu items in the cart -->
                        @if(!empty($cart->getEventItems()))
                            <h3>Your Event Items</h3>
                            <h3>Your Event Items </h3>
                            <hr class="hr-separator">
                            @foreach($cart->getEventItems() as $order)
                                @include('partials.checkout_items')
                            @endforeach
                            <hr class="hr-separator">
                        @endif

                    </div>
                    {{--<hr class="cart-line">--}}
                    @endif

                <!-- Show payment stuff if cart is not empty -->
                    @if($cart->getQuantity() != 0)

                        <div class="cart " id="main-payment-form">
                            <div class="row">
                                <h3 class="cart-review">
                                    Delivery Information
                                </h3>
                                <hr class="cart-sep">

                            </div>
                            <div class="form-group row buyer-info">

                                <label for="full-name">Name</label>
                                <input class="form-control pay-input" maxlength="100"
                                       type="text"
                                       value="@if(env('APP_ENV') !== 'production') {{ 'Daniel Evans' }} @endif"
                                       name="name" id="full-name" placeholder="Full Name">
                                <br>
                                @if(!empty($cart->getOnDemandItems()))
                                    {{-- TODO: change this to use the $cart variable--}}
                                    <div class="delivery-info-wrap">

                                        <label for="location">Where should we deliver your On Demand items? <sup style="color:crimson">*required</sup> </label>
                                        <div class="location-option row">
                                            <label class="radio-inline">
                                                <input type="radio" name="address_loc" id="loc-university" value="1">University
                                                Building
                                            </label>
                                            <label class="radio-inline ">
                                                <input type="radio" name="address_loc" id="loc-address" value="0">Specific
                                                Address
                                            </label>
                                        </div>
                                        <div class="row" id="location-wrap">
                                            <input class="form-control pay-input controls" type="text" maxlength="100"
                                                   name="address"
                                                   value="@if(env('APP_ENV') !== 'production') {{ '12595 Sollace M Freeman Hwy, Sewanee, TN 37375' }} @endif"
                                                   id="location" required>
                                        </div>
                                        <div class="university-wrap" id="university-wrap">
                                            <div class="building-wrap">

                                                <label for="">Building Name</label>
                                                <input type="text" id="building" name="building_name"
                                                       maxlength="150"
                                                       placeholder="Building Name" required class="form-control">
                                            </div>
                                            <div class="select-wrap">
                                                <label for="venmo-username" class="row">Please select</label> <br>
                                                <select class="row custom-select" name="area_type"
                                                        id="inlineFormCustomSelect" required>
                                                    <option class="issue-type" value="Room Number" selected>Room
                                                        Number
                                                    </option>
                                                    <option class="issue-type" value="Room Name">Room Name</option>
                                                </select>
                                            </div>
                                            <div class="room-wrap">
                                                <label for="venmo-username">Room Name or Number</label>
                                                <input type="text" id="room-number" name="room_number"
                                                       maxlength="150"
                                                       placeholder="Room name/number" required class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                @endif
                            </div>

                            <!-- Payment information -->
                            <div class="row">
                                <h3 class="cart-review">
                                    Payment
                                </h3>
                                <hr class="cart-sep">

                            </div>
                            <div class="info-wrapper">

                                <div class="form-group row buyer-info">
                                    <label for="payment-type" id="pay-with-what">Check the box if you would
                                        like to
                                        pay with Venmo.</label>
                                    <input type="checkbox" name="payment_type" id="pay-with-venmo" value="0"
                                           onclick="animate()" data-aim="#venmo-payment-div">
                                    <br>

                                    <div id="venmo-payment-div" class="form-group">

                                        <label for="venmo-username">Venmo Username </label>
                                        <input type="text" id="venmo-username" name="venmo_username"
                                               maxlength="150"
                                               placeholder="Venmo Username" class="form-control">

                                    </div>
                                    <br>

                                    <label for="pay-with-card" id="pay-with-card">Otherwise, fill out the information
                                        below to
                                        pay with a card.</label>


                                    <div id="payment-errors-div" style="display: none">
                                        <br>
                                        <span class="alert alert-danger" id="payment-errors"></span>
                                        <br><br>
                                    </div>

                                    <div id="card-payment-div">

                                        <div class="form-group" id="c-number">
                                            <label>
                                                <span class="row">Card Number</span>
                                                <input class="pay-input form-control"
                                                       type="text"
                                                       id="card-number" size="20"
                                                       data-stripe="number"
                                                       name="card_number"
                                                       value="@if(env('APP_ENV') != "production") {{ 4242424242424242 }} @endif"
                                                       placeholder="############">
                                            </label>
                                            {{--<div class="form-group" id="c-cvc">--}}
                                            <label>
                                                <span class="row">CVC</span>
                                                <input class="pay-input" type="text" size="4" maxlength="4" id="cvc"
                                                       data-stripe="cvc" name="cvc" placeholder="###">
                                            </label>
                                            {{--</div>--}}
                                        </div>

                                        <div class="form-group" id="c-date">
                                            <label>
                                                <span class="row">Expiration</span>
                                                <div class="row">

                                                    <input class="pay-input" type="text" size="2" id="exp-month"
                                                           maxlength="2"
                                                           data-stripe="exp_month" name="expire_month" placeholder="MM">
                                                    <input class="pay-input" type="text" size="4" id="exp-year"
                                                           maxlength="4"
                                                           data-stripe="exp_year" name="expire_year" placeholder="YYYY">
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                </div>


                                <div class="form-group" id="loc-phone">

                                    <label for="email-address">Email Address</label>
                                    <input class="pay-input email-address"
                                           maxlength="100"
                                           placeholder="Please enter your email address"
                                           value="@if(env('APP_ENV') !== 'production') {{ 'seatstest17@gmail.com' }} @endif"
                                           type="email"
                                           name="email_address" id="email-address">
                                    @if($cart->hasOnDemandItems())
                                        <label for="phone-number">Phone Number <span id="reason"> (used by delivery personnel to
                                                contact you when necessary)</span></label>
                                        <input class="form-control pay-input" maxlength="12"
                                               placeholder="10 digits, only numbers"
                                               value="@if(env('APP_ENV') !== 'production') {{ '5555555555' }} @endif"
                                               type="tel"
                                               name="phone_number" id="phone-number">
                                    @endif
                                </div>
                            </div>
                            <div class="total">
                                <p class="summary">Order Summary</p>
                                <div class="summary-wrap">

                                    <div>Cost of Food: <span id="cost-of-food">{{ $bill->getCostOfFood() }}</span></div>
                                    <div>Delivery Fee:
                                        @if($bill->getDiscount() != 0)
                                            <i id="delivery-fee-percentage">you saved {{$bill->getDiscount()}} %!</i>
                                        @endif
                                        <span id="delivery-fee">{{ $bill->getDeliveryFee()  }}</span>
                                    </div>
                                    <div>Subtotal: <span id="subtotal">{{ $bill->getSubtotal() }}</span></div>
                                    <div>Order Total (subtotal + tax): <span
                                                id="total-price">{{ $bill->getTotal() }}</span></div>
                                    <button type="submit" id="pay-now-button" onclick="checkPayNow(event)"
                                            class="checkout-btn">Pay Now
                                    </button>
                                    <p>
                                        <i>*By clicking submit, you are agreeing to the Sewanee Eats <a
                                                    href="{{ route('terms') }}">Terms
                                                and
                                                Conditions</a>.
                                        </i>
                                    </p>
                                </div>
                            </div>


                        </div>
                @endif

                <!-- Show checkout info only if we are on the checkout page -->
                </div>
        </form>
    </div>

    <!-- End payment information -->

    <!-- Strip payment script -->
    <script>
        @if(env('APP_ENV') === "production")
             Stripe.setPublishableKey("{{ env('STRIPE_LIVE_PUBLISHABLE_KEY') }}");
        @else
            Stripe.setPublishableKey("{{ env('STRIPE_TEST_PUBLISHABLE_KEY') }}");
        @endif

    </script>
    <script src="{{ asset('js/Checkout/stripe_checkout.js',env('APP_ENV') === 'production') }}"></script>
    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCRzYmXWGvA4FPHffiFUMyTCWLVSlYL04s&libraries=geometry,places"></script>
    <script src="{{ asset('js/Checkout/delivery_time_estimation.js',env('APP_ENV') === 'production') }}"></script>
    <script src="{{ asset('js/Checkout/checkout.js',env('APP_ENV') === 'production') }}"></script>


@stop
