@extends('layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/cart.css") }}">
    <title>Checkout</title>
    <style>
        #payment-form {
            padding: 10px;
        }
    </style>
    <script>
      function addInstuction(div) {
        // or we can just have a text area there to start
        // but it wouldn't look as pretty I think
        $(div).parent().append(
        "<textarea placeholder='Add special instructions' " +
        "name='special_instructions'></textarea>"
        );
        $(div).hide();
      }
    </script>
@stop

@section('body')
    @if(Session::get('cart'))
        <pre>
            {{ print_r(Session::get('cart')) }}
        </pre>
    @endif
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
                        <div class="menu-item container">
                            <div class="row">
                                <div>{{ $order['menu_item_model']->name }}</div>
                                <div>{{ $order['menu_item_model']->price }}</div>
                                <div>{{ $order['menu_item_model']->description }}</div>
                            </div>
                            <form action="{{ route('updateCart',['id' => $order['menu_item_model']->id]) }}"
                                  method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="cart_item_id" value="{{ $order['menu_item_model']->id }}">
                                <div class="row">
                                    <label for="quantity">Quantity: </label>
                                    <select id="quantity" name="quantity">
                                        <!-- Load up the quantity select input -->
                                        @for($i = 0; $i <= 10; $i++)
                                            @if($i == 0)
                                                <option value="{{ $i }}">Delete</option>
                                            @else
                                            <!-- Make the current quantity the selected option -->
                                                @if($order['quantity'] == $i)
                                                    <option selected
                                                            value="{{ $i }}">{{ $i }}</option>
                                                @else
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endif
                                            @endif
                                        @endfor
                                    </select>
                                </div>
                                <div class="row">
                                    @if(empty($order['special_instructions']))
                                        <button type="button" class="checkoutbtn" onclick="addInstuction(this)">
                                            Add Special Instructions
                                        </button>
                                    @else
                                        <label for="special_instructions">Special instructions</label>
                                        <textarea id="special_instructions"
                                                  name="special_instructions">{{ $order['special_instructions'] }}</textarea>
                                    @endif
                                    <button class="checkoutbtn" type="submit">Update item</button>
                                </div>
                                <div class="divider"></div>
                            </form>
                        </div>
                    </li.cart>
                @endforeach
            </ul.cart>
        @endif
    </div>


    <!-- Show checkout info only if we are on the checkout page -->
    @if(parse_url(url()->current(), PHP_URL_PATH) == '/checkout')

       <div class="cart">

           <h4>Enter your information to pay:</h4>
        <form action="{{ route('handleCheckout') }}" method="POST" id="payment-form">
            {{ csrf_field() }}
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
            <input type="submit" class="checkoutbtn" value="PAY NOW">
        </form>

    @endif


    <div class="container">

    </div>

    <script>
      Stripe.setPublishableKey('pk_test_GALLn3YWDPqPycDBzdxuMz2z');

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
      }

    </script>

       </div>
@stop
