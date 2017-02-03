@extends('layout')

@section('head')
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
        "name='special_instructions[]'>sdafasdfdsfdf</textarea>"
        );
        $(div).hide();
      }
    </script>
@stop

@section('body')
    <br><br><br><br>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="container">
    @if(empty(Session::get('cart')))
        <!-- TODO: need better message here -->
            <h1>Not items in your cart</h1>
        @endif
        <ul class="list-group">
            @foreach(Session::get('cart') as $order)
                <li class="list-group-item">
                    <div class="menu-item">
                        <div>{{ $order['menu_item_model']->name }}</div>
                        <div>{{ $order['menu_item_model']->price }}</div>
                        <div>{{ $order['menu_item_model']->description }}</div>
                        <div>Quantity: {{ $order['quantity'] }}</div>
                        @if(empty($order['special_instructions']))
                            <button class="btn-primary" onclick="addInstuction(this)">Add Special Instructions</button>
                        @else
                            <div>Special Instructions: {{ $order['special_instructions'] }}</div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

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
        <div>Subtotal: {{ $cost_before_fees }}</div>

        <input type="submit" class="btn-primary" value="Total Price: ${{ $total_price }}">
    </form>


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

@stop
