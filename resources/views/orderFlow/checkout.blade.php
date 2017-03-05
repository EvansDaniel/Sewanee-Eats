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

                <div class="row">
                    <h3 class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cart-review">
                        1.Review item(s)
                    </h3>


                </div>


                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="orders">

                    <!-- Loop through all menu items in the cart -->
                    @if(!empty($items['special_items']))
                        <h3>Your Weekly Special Items</h3>
                        @foreach($items['special_items'] as $order)
                            @if(!$loop->last)
                                @for($i = 0; $i < $order['quantity']; $i++)
                                    @include('partials.checkout_items')
                                    <hr class="cart-line">
                                @endfor
                            @else
                                @for($i = 0; $i < $order['quantity']; $i++)
                                    @include('partials.checkout_items')
                                    @if($i != $order['quantity']-1)
                                        <hr class="cart-line">
                                    @endif
                                @endfor
                            @endif
                        @endforeach
                        <hr class="hr-separator">
                    @endif

                </div>

                <hr class="cart-line">

            @endif
            @if(!empty(Session::get('cart')) && Session::get('cart') != null)

                <div class="cart " id="main-payment-form" style="">
                    <!-- Payment information -->


                    <div class="row">
                        <h3 class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cart-review">
                            2. Enter your information to pay:
                        </h3>
                    </div>

                    <div id="ct-ct">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-10 col-xs-10">

                                    <label for="email-address">Name</label>
                                    <input class="form-control pay-input" maxlength="100"
                                           type="text"
                                           name="name" id="full-name">
                                    <br>
                                    <label for="pay-with-venmo" id="pay-with-what">Check the box if you would like to
                                        pay with Venmo.</label>
                                    <input type="checkbox" name="pay_with_venmo" id="pay-with-venmo" value="0">
                                    <br>

                                    <div id="venmo-payment-div" class="form-group">

                                        <label for="venmo-username">Venmo Username</label>
                                        <input type="text" id="venmo-username" name="venmo_username" maxlength="150"
                                               placeholder="Type in your venmo username" class="form-control">

                                    </div>
                                </div>
                            </div>
                            <br>

                            <label for="pay-with-card" id="pay-with-card">Otherwise, fill out the information below to
                                pay with a card.</label>

                            <div class="row">

                                <div id="payment-errors-div" style="display: none">
                                    <br>
                                    <span class="alert alert-danger" id="payment-errors"></span>
                                    <br><br>
                                </div>

                                <div id="card-payment-div">

                                    <div class="form-group  col-lg-3 col-md-3 col-sm-10   col-xs-10" id="c-number">
                                        <label>
                                            <span class="row">Card Number</span>
                                            <input class="pay-input form-control" type="text" id="card-number" size="20"
                                                   data-stripe="number">
                                        </label>
                                    </div>

                                    <div class="form-group col-lg-3 col-md-3 col-sm-10  col-xs-10" id="c-date">
                                        <label>
                                            <span class="row">Expiration (MM/YY)</span>
                                            <input class="pay-input" type="text" size="2" id="exp-month" maxlength="2"
                                                   data-stripe="exp_month">
                                        </label>
                                        {{--<span> / </span>--}}
                                        <input class="pay-input" type="text" size="4" id="exp-year" maxlength="4"
                                               data-stripe="exp_year">
                                    </div>

                                    <div class="form-group col-lg-3 col-md-3 col-sm-10  col-xs-10" id="c-cvc">
                                        <label>
                                            <span class="row">CVC</span>
                                            <input class="pay-input" type="text" size="4" maxlength="4" id="cvc"
                                                   data-stripe="cvc">
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group" id="loc-phone">
                                @if(!empty($items['non_special_items']))
                                    <label for="location">Where should we deliver your On Demand items?</label>
                                    <input class="form-control pay-input" type="text" maxlength="100" name="location"
                                           id="location">
                                @endif

                                <label for="email-address">Email Address</label>
                                <input class="form-control pay-input" maxlength="100"
                                       placeholder="Please enter your email address"
                                       type="email"
                                       name="email_address" id="email-address">
                                {{--
                               <input class="form-control pay-input" maxlength="10" placeholder="10 digits, only numbers"
                                      type="tel"
                                      name="phone_number" id="phone-number">--}}
                            </div>
                            <div>Subtotal (w/ delivery fee included): $<span id="subtotal">{{ $subtotal }}</span></div>
                            <div>Order Total (subtotal + tax): $<span id="total-price">{{ $total_price }}</span></div>
                            <button type="submit" id="pay-now-button" onclick="checkPayNow(event)"
                                    class="checkout-btn">Pay Now
                            </button>

                            <br>
                            <br>

                            <i>*By clicking submit, you are agreeing to the Sewanee Eats <a href="terms">Terms and
                                    Conditions</a>.</i>
                        </div>
                    </div>
                @endif

                <!-- Show checkout info only if we are on the checkout page -->
                </div>
        </form>
    </div>

    <style>


        .hr-separator {
            display: block;
            background-color: rebeccapurple;
            height: 1px;
            border: 0;
            border-top: 1px solid #ccc;
            margin-top: 2.5%;
            margin-bottom: 2.5%;
            padding: 0;
        }
    </style>
    <!-- End payment information -->

    <!-- Strip payment script -->
    <script>
      @if(env('APP_ENV') === "production")
          Stripe.setPublishableKey({{ env('STRIPE_LIVE_PUBLISHABLE_KEY') }});
      @else
          Stripe.setPublishableKey({{ env('STRIPE_TEST_PUBLISHABLE_KEY') }});
      @endif

      $(function () {
        var $form = $('#payment-form');
        $form.submit(function (event) {
          $('#pay-now-button').prop('disabled', true);
          if (!$('#pay-with-venmo').is(':checked')) {
            // Disable the submit button to prevent repeated clicks:

            // TODO: see where this fits into the current set up
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
              /*$form.find('.submit').prop('disabled', false);*/
            $('#pay-now-button').prop('disabled', false);
            event.preventDefault();
            return false;
          }
        });
      });

      function stripeResponseHandler(status, response) {
        // Grab the form:
        var $form = $('#payment-form');

        p('in response handler');
        if (response.error) { // Problem!

          // Show the errors on the form:
          $('#payment-errors-div').show();
          $('#payment-errors').text(response.error.message);
          $form.find('.submit').prop('disabled', true); // Re-enable submission

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
    <script>
      $('#venmo-payment-div').hide();
      $('#pay-with-venmo').on('change', function () {
        var box = $(this);
        if (box.is(':checked')) {
          // set pay with venmo to true
          $('#pay-with-venmo').val(1);
          $('#venmo-payment-div').show(350);
          $('#card-payment-div').hide(350);
          $('#pay-with-card').hide(350);
        } else {
          // set pay with venmo to false
          $('#pay-with-venmo').val(0);
          $('#venmo-payment-div').hide(350);
          $('#card-payment-div').show(350);
          $('#pay-with-card').show(350);
        }
      })
    </script>
@stop
