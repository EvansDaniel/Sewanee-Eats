@extends('main_layout')

@section('head')
    <title>Sewanee Eats</title>
    <link rel="stylesheet" href={{ asset('css/home.css',env('APP_ENV') !== 'local')  }}>
@stop
@section('body')
    <div id="terms" class="container">
        <h6> RETURNS </h6>
        No returns are provided for the items you have already ordered.

        <h6>REFUNDS </h6>
        In case the product(s) you ordered is/are not available, SewaneeEats will refund you the total cost of your
        product(s), delivery fee included.

        <h6>DELIVERY PLACE AND TIMING</h6>
        <h6> 1. Weekly Specials </h6>
        Weekly specials are delivered once a week at a place convenient to you. The order is to be processed within a
        specific time, is going to be delivered at the convenient place of your choice, and at a time that listed on the
        website(checkout/cart page).
        You are responsible for picking your order at specified time that appears on the checkout page(cart page).
        SewaneeEats will not wait for more than 15 minutes for you to come pick up your order.

        <h6> 2. Regular Daily Deliveries(for inside campus restaurants and Monteagle) </h6>

        The time of delivery is an estimated time based on the amount of time it takes to drive to the place and how
        long your should take.

        The carriers of your order will stay in touch with you. Sewanee Eats also provides a way of tracking your order.

        The order is always going to be delivered at your place of convenience.

        Sewanee Eats does not own any restaurant logos or photos posted unless stated otherwise. All logo credit belongs
        to the restaurants themselves.

    </div>
    <style>
        #terms {
            font-family: "Lato", sans-serif;
            font-size: 14px;
        }

        h6 {
            font-size: 17px;
        }
    </style>
@stop