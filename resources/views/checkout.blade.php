@extends('layout')

@section('head')
    <title>Checkout</title>
@stop

@section('body')
    <br>
    <br>
    <br>
    <br>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <style>
        #payment-form {
            padding: 10px;
        }
    </style>

    <div class="container">
    @if(count($checkoutItems) == 0)
        <!-- TODO: need better message here -->
            <h1>Not items in your cart</h1>
        @endif
        <ul class="list-group">
            @foreach($checkoutItems as $item)
                <li class="list-group-item">
                    <div>{{ $item->name }}</div>

                    <div>{{ $item->price }}</div>

                    <div>{{ $item->description }}</div>
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


        <input type="submit" class="submit" value="{{ $sum }}">
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
        ;
    </script>

@stop