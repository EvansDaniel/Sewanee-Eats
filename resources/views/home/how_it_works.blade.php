@extends('layout')


@section('body')
    <style>
        p {
            width: 50%;
            font-size: 18px;
        }
    </style>
    <div align="center">
        <h2>How do I place an order?</h2>
        <p>Ordering from your favorite place with Sewanee Eats is a breeze! Just
            pick a restaurant then add your desired
            food items to the cart.
        </p>

        <h2>How do I pay for my food?</h2>


        <p>Once you've added food to your cart, click the Checkout link in the top right corner and you will be
            directed to
            a page where you can pay for your order. You can pay the following ways:
        <p>

        <h3>Paying With Venmo</h3>


        <p>To pay with Venmo, leave your Venmo username in the empty box and click
            'Submit'. We will immediately send you a request on Venmo
            for the amount listed as your order total. Once you have paid, you are good to go!</p>

        <h3>Pay with Card</h3>

        <p>
            We accept all major debit and credit cards. Simply enter your information and click 'Submit'. Our payment
            processor is
            secure and all your information is safe. We process all card payments using <a
                    href="https://www.stripe.com">Stripe</a>, a secure payment processor.
        </p>

        <h2>I've paid, now what?</h2>
        <p>
            Once you have paid for your order, you will receive an email receipt from Sewanee Eats confirming your
            order. One of our delivery
            carriers will go and pick up your order and deliver it to you within 30-45 minutes.
        <p>

        <h2>Help! There was a problem with my order!</h2>
        <p>
            We will always do our best to resolve your order problems. If there is an issue with your order, please
            contact us immediately <a href="{{ route('support') }}">here</a> and
            we will fix the issue.
        </p>

        <h2>What are weekly specials?</h2>
        <p><a id="specials"></a>Each week, we will have food specials from
            establishments that might be a little farther from
            campus than our usuals! For example, we'll deliver Chick-fil-a or Zaxby's to your doorstep! Every Monday
            morning, we will
            post what the weekly special restaurant is for the week! You can then place your order for that restaurant,
            and
            we'll send you an email to confirm. Then, on the day that the special is on, we'll deliver your food to you.
        </p>

    </div>


@stop

