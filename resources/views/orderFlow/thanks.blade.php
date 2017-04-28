@extends('main.main_layout')
@section('head')
    <title>Sewanee Eats| Thank you!</title>
@stop
@section('body')
    <link rel="stylesheet" href="{{asset('css/thanks.css',env('APP_ENV') != 'local')}}">
    <div class="container">
        <h3 align="center">Your Order Has Been Confirmed!</h3>
        <hr>
        <h3 align="center">
            Thank you for using SewaneeEats food delivery services. An order confirmation email has been sent to the
            email address you provided.
        </h3>
        <div id="weekly-special-div">
            <div align="center">
                <h4>
                    Your Order Confirmation Number is
                    <span>
                            {{ $new_order->id }}
                        </span>
                    <br><br>
                {{--You can use this number, along with our <span><a
                            href="{{ route('findMyOrder') }}">Find My Order</a></span>
                service, to view your order--}}

                <!-- TODO: find my order will also provide tracking if the order is on demand -->
                </h4>
                @if($new_order->hasOrderType($weekly_special_order_type))
                    <p>The delivery time and location for the special you ordered are in the confirmation email sent
                        to {{ $new_order->email_of_customer }}</p>
                @endif
            </div>

        </div>


        <div id="order-payment-info">
            <h2>Payment</h2>
            <hr>
            <h4>
                Since you requested to pay with Venmo, one of our managers will be in touch soon to proceed with
                the payment.
                {{--@if(!empty($new_order))

                    Please come and pick up your order at 12:30pm in the BC.
                @endif--}}

            </h4>
        </div>

        <div id="order-support" align="center   ">
            <h5>
                Send us an email from our <span><a href="{{ route('support') }}">support</a></span> page if you have
                any
                questions or concerns about your order. Be sure to provide your order confirmation number, which is
                also
                in your confirmation email
            </h5>
        </div>

        <div align="center">
            <h4>
                To place another order, start <a href="{{ route('list_restaurants') }}">here</a>
            </h4>
        </div>
@stop
