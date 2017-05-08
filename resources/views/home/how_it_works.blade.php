@extends('main.main_layout')


@section('body')
    <style>
        .hw-wrks {
            width: 50%;
            margin: auto;
            font-size: 18px;
            color: black;
            font-family: "Lato", sans-serif;
        }
        .hw-wrks h2, .hw-wrks h3 {
            color: rebeccapurple;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 20px;
            margin-top: 35px;
        }
        .hw-wrks h3{
            margin-top: 15px;
            font-size: 18px;
            color: darkorange;
            text-transform: none;
            text-decoration: underline;
        }
        @media only screen and  (max-width: 768px){
            .hw-wrks{
                width: 100%;
            }
           .hw-wrks h2, .hw-wrks h3 {
                color: rebeccapurple;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 16px;
            }
           .hw-wrks h3{
               font-size: 16px;
               color: darkorange;
               text-transform: none;
               text-decoration: underline;
           }
            .hw-wrks p{
                font-size: 14px;
            }
        }
        footer{
            padding: 6px 12px;
            margin-left: -30px;
            margin-top: -25px;
        }
    </style>
    <div align="center" class="hw-wrks">
        <h2>1. How do I place an order?</h2>
        <p class="works">Ordering from your favorite place with Sewanee Eats is a breeze! Just
            pick a restaurant then add your desired
            food items to the cart.
        </p>

        <h2>2. How do I pay for my food?</h2>


        <p class="works">Once you've added food to your cart, click the Checkout link in the top right corner and you
            will be directed to a page where you can pay for your order. You can pay the following ways:
        <p>

        <h3>a. Paying With Venmo</h3>


        <p class="works">To pay with Venmo, leave your Venmo username in the empty box and click
            'Submit'. We will immediately send you a request on Venmo
            for the amount listed as your order total. Once you have paid, you are good to go!</p>

        <h3>b. Pay with Card</h3>

        <p class="works">
            We accept all major debit and credit cards. Simply enter your information and click 'Submit'. Our payment
            processor is
            secure and all your information is safe. We process all card payments using <a
                    href="https://www.stripe.com">Stripe</a>, a secure payment processor.
        </p>

        <h2>3. I've paid, now what?</h2>
        <p class="works">
            Once you have paid for your order, you will receive an email receipt from Sewanee Eats confirming your
            order. One of our delivery
            carriers will go and pick up your order and deliver it to you within 30-45 minutes.
        <p>

        <h3 id="help"> a. Help! There was a problem with my order!</h3>
        <p class="works">
            We will always do our best to resolve your order problems. If there is an issue with your order, please
            contact us immediately <a href="{{ route('support') }}">here</a> and
            we will fix the issue.
        </p>

        <h2>4. What are specials?</h2>
        <p class="works"><a id="specials"></a>
        Each week, SewaneeEats will have food specials from establishments that might be a little farther
        from campus than our usuals!
        For example, we'll deliver Chick-fil-a, Zaxby's, Chicken Itza, ...!
            Every Monday morning, we will post what the special restaurant delivery is for the week! You can then
            place your order for that restaurant, and we'll send you an email to confirm. Your order will be delivered
            to the location specified during checkout and in the email. If you have any issues, don't hesitate to
            contact us
            via the <a href="{{ route('support') }}">support</a> page
        </p>
    </div>


@stop

