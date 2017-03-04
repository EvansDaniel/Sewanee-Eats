@extends('layout')
@section('head')
    <title>Sewanee Eats| Thank you!</title>
@stop
@section('body')
    <div class="container">
        <h3 align="center">Your Order Has Been Confirmed!</h3>
        <hr>
        <h3 align="center">
            Thank you for using SewaneeEats food delivery services. An order confirmation email has been sent to the
            email address you provided.
        </h3>
        @if(!empty($weekly_special_order))
            <div id="weekly-special-div">
                <div align="center">
                    <h4>
                        Your Weekly Special Order Confirmation Number is {{ $weekly_special_order->id }}
                        <br><br>
                    {{-- You can use this number, along with our <span><a
                                 href="{{ route('findMyOrder') }}">Find My Order</a></span>
                     service, to view your order--}}
                    <!-- TODO: find my order will also provide tracking if the order is on demand -->
                    </h4>
                </div>
                <a href="{{ route('orderSummary',['order_id' => $weekly_special_order->id]) }}">
                    <button class="btn btn-primary">View Your Weekly Special Order Summary</button>
                </a>
            </div>
        @endif
        @if(!empty($on_demand_order))
            <div class="row" id="on-demand-order-div">
                <a href="{{ route('orderSummary',['order_id' => $on_demand_order->id]) }}">
                    <button>View Your On Demand Order Summary</button>
                </a>
                <h4>
                    Your On Demand Order Confirmation Number is {{ $on_demand_order->id }}
                    <br><br>
                {{--You can use this number, along with our <span><a
                            href="{{ route('findMyOrder') }}">Find My Order</a></span>
                service, to view your order--}}
                <!-- TODO: find my order will also provide tracking if the order is on demand -->
                </h4>
            </div>
        @endif
        @if((!empty($on_demand_order) && $on_demand_order->paid_with_venmo) ||
            (!empty($weekly_special_order) && $weekly_special_order->paid_with_venmo))
            <div id="order-payment-info">
                <h2>Payment</h2>
                <h4>
                    Since you requested to pay with Venmo, one of our manangers will be in touch soon to proceed with
                    the payment
                    @if(!empty($on_demand_order))
                        Once payment is confirmed, our drivers will be notified and will begin servicing your order
                    @endif
                </h4>
            </div>
        @endif
        <div id="order-support" align="center   ">
            <h4>
                Send us an email from our <span><a href="{{ route('support') }}">support</a></span> page if you have any
                questions or concerns about your order. Be sure to provide your order confirmation number, which is also
                in your confirmation email
            </h4>
        </div>
    </div>
    <style>

    </style>
@stop